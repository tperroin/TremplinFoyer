<?php  



namespace Symfony\Component\HttpKernel\Controller
{

use Symfony\Component\HttpFoundation\Request;


interface ControllerResolverInterface
{
    
    public function getController(Request $request);

    
    public function getArguments(Request $request, $controller);
}
}
 



namespace Symfony\Component\HttpKernel\Controller
{

use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;


class ControllerResolver implements ControllerResolverInterface
{
    private $logger;

    
    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    
    public function getController(Request $request)
    {
        if (!$controller = $request->attributes->get('_controller')) {
            if (null !== $this->logger) {
                $this->logger->warn('Unable to look for the controller as the "_controller" parameter is missing');
            }

            return false;
        }

        if (is_array($controller) || (is_object($controller) && method_exists($controller, '__invoke'))) {
            return $controller;
        }

        if (false === strpos($controller, ':')) {
            if (method_exists($controller, '__invoke')) {
                return new $controller;
            } elseif (function_exists($controller)) {
                return $controller;
            }
        }

        list($controller, $method) = $this->createController($controller);

        if (!method_exists($controller, $method)) {
            throw new \InvalidArgumentException(sprintf('Method "%s::%s" does not exist.', get_class($controller), $method));
        }

        return array($controller, $method);
    }

    
    public function getArguments(Request $request, $controller)
    {
        if (is_array($controller)) {
            $r = new \ReflectionMethod($controller[0], $controller[1]);
        } elseif (is_object($controller) && !$controller instanceof \Closure) {
            $r = new \ReflectionObject($controller);
            $r = $r->getMethod('__invoke');
        } else {
            $r = new \ReflectionFunction($controller);
        }

        return $this->doGetArguments($request, $controller, $r->getParameters());
    }

    protected function doGetArguments(Request $request, $controller, array $parameters)
    {
        $attributes = $request->attributes->all();
        $arguments = array();
        foreach ($parameters as $param) {
            if (array_key_exists($param->name, $attributes)) {
                $arguments[] = $attributes[$param->name];
            } elseif ($param->getClass() && $param->getClass()->isInstance($request)) {
                $arguments[] = $request;
            } elseif ($param->isDefaultValueAvailable()) {
                $arguments[] = $param->getDefaultValue();
            } else {
                if (is_array($controller)) {
                    $repr = sprintf('%s::%s()', get_class($controller[0]), $controller[1]);
                } elseif (is_object($controller)) {
                    $repr = get_class($controller);
                } else {
                    $repr = $controller;
                }

                throw new \RuntimeException(sprintf('Controller "%s" requires that you provide a value for the "$%s" argument (because there is no default value or because there is a non optional argument after this one).', $repr, $param->name));
            }
        }

        return $arguments;
    }

    
    protected function createController($controller)
    {
        if (false === strpos($controller, '::')) {
            throw new \InvalidArgumentException(sprintf('Unable to find controller "%s".', $controller));
        }

        list($class, $method) = explode('::', $controller, 2);

        if (!class_exists($class)) {
            throw new \InvalidArgumentException(sprintf('Class "%s" does not exist.', $class));
        }

        return array(new $class(), $method);
    }
}
}
 



namespace Symfony\Bundle\FrameworkBundle\Controller
{

use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolver as BaseControllerResolver;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\ControllerNameParser;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;


class ControllerResolver extends BaseControllerResolver
{
    protected $container;
    protected $parser;

    
    public function __construct(ContainerInterface $container, ControllerNameParser $parser, LoggerInterface $logger = null)
    {
        $this->container = $container;
        $this->parser = $parser;

        parent::__construct($logger);
    }

    
    protected function createController($controller)
    {
        if (false === strpos($controller, '::')) {
            $count = substr_count($controller, ':');
            if (2 == $count) {
                                $controller = $this->parser->parse($controller);
            } elseif (1 == $count) {
                                list($service, $method) = explode(':', $controller, 2);

                return array($this->container->get($service), $method);
            } else {
                throw new \LogicException(sprintf('Unable to parse the controller name "%s".', $controller));
            }
        }

        list($class, $method) = explode('::', $controller, 2);

        if (!class_exists($class)) {
            throw new \InvalidArgumentException(sprintf('Class "%s" does not exist.', $class));
        }

        $controller = new $class();
        if ($controller instanceof ContainerAwareInterface) {
            $controller->setContainer($this->container);
        }

        return array($controller, $method);
    }
}
}
 



namespace JMS\DiExtraBundle\HttpKernel
{

use Metadata\ClassHierarchyMetadata;
use JMS\DiExtraBundle\Metadata\ClassMetadata;
use CG\Core\DefaultNamingStrategy;
use CG\Proxy\Enhancer;
use JMS\AopBundle\DependencyInjection\Compiler\PointcutMatchingPass;
use JMS\DiExtraBundle\Generator\DefinitionInjectorGenerator;
use JMS\DiExtraBundle\Generator\LookupMethodClassGenerator;
use JMS\DiExtraBundle\DependencyInjection\Dumper\PhpDumper;
use Metadata\MetadataFactory;
use Symfony\Component\DependencyInjection\Compiler\InlineServiceDefinitionsPass;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\Compiler\ResolveDefinitionTemplatesPass;
use Symfony\Component\DependencyInjection\Parameter;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\ConfigCache;
use Symfony\Bundle\FrameworkBundle\Controller\ControllerResolver as BaseControllerResolver;

class ControllerResolver extends BaseControllerResolver
{
    protected function createController($controller)
    {
        if (false === $pos = strpos($controller, '::')) {
            $count = substr_count($controller, ':');
            if (2 == $count) {
                                $controller = $this->parser->parse($controller);
                $pos = strpos($controller, '::');
            } elseif (1 == $count) {
                                list($service, $method) = explode(':', $controller);

                return array($this->container->get($service), $method);
            } else {
                throw new \LogicException(sprintf('Unable to parse the controller name "%s".', $controller));
            }
        }

        $class = substr($controller, 0, $pos);
        $method = substr($controller, $pos+2);

        if (!class_exists($class)) {
            throw new \InvalidArgumentException(sprintf('Class "%s" does not exist.', $class));
        }

        $injector = $this->createInjector($class);
        $controller = call_user_func($injector, $this->container);

        if ($controller instanceof ContainerAwareInterface) {
            $controller->setContainer($this->container);
        }

        return array($controller, $method);
    }

    public function createInjector($class)
    {
        $filename = $this->container->getParameter('jms_di_extra.cache_dir').'/controller_injectors/'.str_replace('\\', '', $class).'.php';
        $cache = new ConfigCache($filename, $this->container->getParameter('kernel.debug'));

        if (!$cache->isFresh()) {
            $metadata = $this->container->get('jms_di_extra.metadata.metadata_factory')->getMetadataForClass($class);
            if (null === $metadata) {
                $metadata = new ClassHierarchyMetadata();
                $metadata->addClassMetadata(new ClassMetadata($class));
            }

                                                if (null !== $metadata->getOutsideClassMetadata()->id
                    && 0 !== strpos($metadata->getOutsideClassMetadata()->id, '_jms_di_extra.unnamed.service')) {
                return;
            }

            $this->prepareContainer($cache, $filename, $metadata, $class);
        }

        if ( ! class_exists($class.'__JMSInjector', false)) {
            require $filename;
        }

        return array($class.'__JMSInjector', 'inject');
    }

    private function prepareContainer($cache, $containerFilename, $metadata, $className)
    {
        $container = new ContainerBuilder();
        $container->setParameter('jms_aop.cache_dir', $this->container->getParameter('jms_di_extra.cache_dir'));
        $def = $container
            ->register('jms_aop.interceptor_loader', 'JMS\AopBundle\Aop\InterceptorLoader')
            ->addArgument(new Reference('service_container'))
            ->setPublic(false)
        ;

                $ref = $metadata->getOutsideClassMetadata()->reflection;
        while ($ref && false !== $filename = $ref->getFilename()) {
            $container->addResource(new FileResource($filename));
            $ref = $ref->getParentClass();
        }

                $definitions = $this->container->get('jms_di_extra.metadata.converter')->convert($metadata);
        $serviceIds = $parameters = array();

        $controllerDef = array_pop($definitions);
        $container->setDefinition('controller', $controllerDef);

        foreach ($definitions as $id => $def) {
            $container->setDefinition($id, $def);
        }

        $this->generateLookupMethods($controllerDef, $metadata);

        $config = $container->getCompilerPassConfig();
        $config->setOptimizationPasses(array());
        $config->setRemovingPasses(array());
        $config->addPass(new ResolveDefinitionTemplatesPass());
        $config->addPass(new PointcutMatchingPass($this->container->get('jms_aop.pointcut_container')->getPointcuts()));
        $config->addPass(new InlineServiceDefinitionsPass());
        $container->compile();

        if (!file_exists($dir = dirname($containerFilename))) {
            if (false === @mkdir($dir, 0777, true)) {
                throw new \RuntimeException(sprintf('Could not create directory "%s".', $dir));
            }
        }

        static $generator;
        if (null === $generator) {
            $generator = new DefinitionInjectorGenerator();
        }

        $cache->write($generator->generate($container->getDefinition('controller'), $className), $container->getResources());
    }

    private function generateLookupMethods($def, $metadata)
    {
        $found = false;
        foreach ($metadata->classMetadata as $cMetadata) {
            if (!empty($cMetadata->lookupMethods)) {
                $found = true;
                break;
            }
        }

        if (!$found) {
            return;
        }

        $generator = new LookupMethodClassGenerator($metadata);
        $outerClass = $metadata->getOutsideClassMetadata()->reflection;

        if ($file = $def->getFile()) {
            $generator->setRequiredFile($file);
        }

        $enhancer = new Enhancer(
            $outerClass,
            array(),
            array(
                $generator,
            )
        );

        $filename = $this->container->getParameter('jms_di_extra.cache_dir').'/lookup_method_classes/'.str_replace('\\', '-', $outerClass->name).'.php';
        $enhancer->writeClass($filename);

        $def->setFile($filename);
        $def->setClass($enhancer->getClassName($outerClass));
        $def->addMethodCall('__jmsDiExtra_setContainer', array(new Reference('service_container')));
    }
}
}
 



namespace Monolog\Formatter
{


interface FormatterInterface
{
    
    public function format(array $record);

    
    public function formatBatch(array $records);
}
}
 



namespace Monolog\Formatter
{


class NormalizerFormatter implements FormatterInterface
{
    const SIMPLE_DATE = "Y-m-d H:i:s";

    protected $dateFormat;

    
    public function __construct($dateFormat = null)
    {
        $this->dateFormat = $dateFormat ?: static::SIMPLE_DATE;
    }

    
    public function format(array $record)
    {
        return $this->normalize($record);
    }

    
    public function formatBatch(array $records)
    {
        foreach ($records as $key => $record) {
            $records[$key] = $this->format($record);
        }

        return $records;
    }

    protected function normalize($data)
    {
        if (null === $data || is_scalar($data)) {
            return $data;
        }

        if (is_array($data) || $data instanceof \Traversable) {
            $normalized = array();

            foreach ($data as $key => $value) {
                $normalized[$key] = $this->normalize($value);
            }

            return $normalized;
        }

        if ($data instanceof \DateTime) {
            return $data->format($this->dateFormat);
        }

        if (is_object($data)) {
            return sprintf("[object] (%s: %s)", get_class($data), $this->toJson($data));
        }

        if (is_resource($data)) {
            return '[resource]';
        }

        return '[unknown('.gettype($data).')]';
    }

    protected function toJson($data)
    {
        if (version_compare(PHP_VERSION, '5.4.0', '>=')) {
            return json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }

        return json_encode($data);
    }
}
}
 



namespace Monolog\Formatter
{


class LineFormatter extends NormalizerFormatter
{
    const SIMPLE_FORMAT = "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n";

    protected $format;

    
    public function __construct($format = null, $dateFormat = null)
    {
        $this->format = $format ?: static::SIMPLE_FORMAT;
        parent::__construct($dateFormat);
    }

    
    public function format(array $record)
    {
        $vars = parent::format($record);

        $output = $this->format;
        foreach ($vars['extra'] as $var => $val) {
            if (false !== strpos($output, '%extra.'.$var.'%')) {
                $output = str_replace('%extra.'.$var.'%', $this->convertToString($val), $output);
                unset($vars['extra'][$var]);
            }
        }
        foreach ($vars as $var => $val) {
            $output = str_replace('%'.$var.'%', $this->convertToString($val), $output);
        }

        return $output;
    }

    public function formatBatch(array $records)
    {
        $message = '';
        foreach ($records as $record) {
            $message .= $this->format($record);
        }

        return $message;
    }

    protected function normalize($data)
    {
        if (is_bool($data) || is_null($data)) {
            return var_export($data, true);
        }

        return parent::normalize($data);
    }

    protected function convertToString($data)
    {
        if (null === $data || is_scalar($data)) {
            return (string) $data;
        }

        if (version_compare(PHP_VERSION, '5.4.0', '>=')) {
            return json_encode($this->normalize($data), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }

        return stripslashes(json_encode($this->normalize($data)));
    }
}
}
 



namespace Monolog\Handler
{

use Monolog\Formatter\FormatterInterface;


interface HandlerInterface
{
    
    public function isHandling(array $record);

    
    public function handle(array $record);

    
    public function handleBatch(array $records);

    
    public function pushProcessor($callback);

    
    public function popProcessor();

    
    public function setFormatter(FormatterInterface $formatter);

    
    public function getFormatter();
}
}
 



namespace Monolog\Handler
{

use Monolog\Logger;
use Monolog\Formatter\FormatterInterface;
use Monolog\Formatter\LineFormatter;


abstract class AbstractHandler implements HandlerInterface
{
    protected $level = Logger::DEBUG;
    protected $bubble = false;

    
    protected $formatter;
    protected $processors = array();

    
    public function __construct($level = Logger::DEBUG, $bubble = true)
    {
        $this->level = $level;
        $this->bubble = $bubble;
    }

    
    public function isHandling(array $record)
    {
        return $record['level'] >= $this->level;
    }

    
    public function handleBatch(array $records)
    {
        foreach ($records as $record) {
            $this->handle($record);
        }
    }

    
    public function close()
    {
    }

    
    public function pushProcessor($callback)
    {
        if (!is_callable($callback)) {
            throw new \InvalidArgumentException('Processors must be valid callables (callback or object with an __invoke method), '.var_export($callback, true).' given');
        }
        array_unshift($this->processors, $callback);
    }

    
    public function popProcessor()
    {
        if (!$this->processors) {
            throw new \LogicException('You tried to pop from an empty processor stack.');
        }

        return array_shift($this->processors);
    }

    
    public function setFormatter(FormatterInterface $formatter)
    {
        $this->formatter = $formatter;
    }

    
    public function getFormatter()
    {
        if (!$this->formatter) {
            $this->formatter = $this->getDefaultFormatter();
        }

        return $this->formatter;
    }

    
    public function setLevel($level)
    {
        $this->level = $level;
    }

    
    public function getLevel()
    {
        return $this->level;
    }

    
    public function setBubble($bubble)
    {
        $this->bubble = $bubble;
    }

    
    public function getBubble()
    {
        return $this->bubble;
    }

    public function __destruct()
    {
        try {
            $this->close();
        } catch (\Exception $e) {
                    }
    }

    
    protected function getDefaultFormatter()
    {
        return new LineFormatter();
    }
}
}
 



namespace Monolog\Handler
{


abstract class AbstractProcessingHandler extends AbstractHandler
{
    
    public function handle(array $record)
    {
        if ($record['level'] < $this->level) {
            return false;
        }

        $record = $this->processRecord($record);

        $record['formatted'] = $this->getFormatter()->format($record);

        $this->write($record);

        return false === $this->bubble;
    }

    
    abstract protected function write(array $record);

    
    protected function processRecord(array $record)
    {
        if ($this->processors) {
            foreach ($this->processors as $processor) {
                $record = call_user_func($processor, $record);
            }
        }

        return $record;
    }
}
}
 



namespace Monolog\Handler
{

use Monolog\Handler\FingersCrossed\ErrorLevelActivationStrategy;
use Monolog\Handler\FingersCrossed\ActivationStrategyInterface;
use Monolog\Logger;


class FingersCrossedHandler extends AbstractHandler
{
    protected $handler;
    protected $activationStrategy;
    protected $buffering = true;
    protected $bufferSize;
    protected $buffer = array();
    protected $stopBuffering;

    
    public function __construct($handler, $activationStrategy = null, $bufferSize = 0, $bubble = true, $stopBuffering = true)
    {
        if (null === $activationStrategy) {
            $activationStrategy = new ErrorLevelActivationStrategy(Logger::WARNING);
        }
        if (!$activationStrategy instanceof ActivationStrategyInterface) {
            $activationStrategy = new ErrorLevelActivationStrategy($activationStrategy);
        }

        $this->handler = $handler;
        $this->activationStrategy = $activationStrategy;
        $this->bufferSize = $bufferSize;
        $this->bubble = $bubble;
        $this->stopBuffering = $stopBuffering;
    }

    
    public function isHandling(array $record)
    {
        return true;
    }

    
    public function handle(array $record)
    {
        if ($this->buffering) {
            $this->buffer[] = $record;
            if ($this->bufferSize > 0 && count($this->buffer) > $this->bufferSize) {
                array_shift($this->buffer);
            }
            if ($this->activationStrategy->isHandlerActivated($record)) {
                if ($this->stopBuffering) {
                    $this->buffering = false;
                }
                if (!$this->handler instanceof HandlerInterface) {
                    $this->handler = call_user_func($this->handler, $record, $this);
                }
                if (!$this->handler instanceof HandlerInterface) {
                    throw new \RuntimeException("The factory callback should return a HandlerInterface");
                }
                $this->handler->handleBatch($this->buffer);
                $this->buffer = array();
            }
        } else {
            $this->handler->handle($record);
        }

        return false === $this->bubble;
    }

    
    public function reset()
    {
        $this->buffering = true;
    }
}
}
 



namespace Monolog\Handler
{

use Monolog\Logger;


class StreamHandler extends AbstractProcessingHandler
{
    protected $stream;
    protected $url;

    
    public function __construct($stream, $level = Logger::DEBUG, $bubble = true)
    {
        parent::__construct($level, $bubble);
        if (is_resource($stream)) {
            $this->stream = $stream;
        } else {
            $this->url = $stream;
        }
    }

    
    public function close()
    {
        if (is_resource($this->stream)) {
            fclose($this->stream);
        }
        $this->stream = null;
    }

    
    protected function write(array $record)
    {
        if (null === $this->stream) {
            if (!$this->url) {
                throw new \LogicException('Missing stream url, the stream can not be opened. This may be caused by a premature call to close().');
            }
            $errorMessage = null;
            set_error_handler(function ($code, $msg) use (&$errorMessage) {
                $errorMessage = preg_replace('{^fopen\(.*?\): }', '', $msg);
            });
            $this->stream = fopen($this->url, 'a');
            restore_error_handler();
            if (!is_resource($this->stream)) {
                $this->stream = null;
                throw new \UnexpectedValueException(sprintf('The stream or file "%s" could not be opened: '.$errorMessage, $this->url));
            }
        }
        fwrite($this->stream, (string) $record['formatted']);
    }
}
}
 



namespace Monolog\Handler
{

use Monolog\Logger;


class TestHandler extends AbstractProcessingHandler
{
    protected $records = array();
    protected $recordsByLevel = array();

    public function getRecords()
    {
        return $this->records;
    }

    public function hasEmergency($record)
    {
        return $this->hasRecord($record, Logger::EMERGENCY);
    }

    public function hasAlert($record)
    {
        return $this->hasRecord($record, Logger::ALERT);
    }

    public function hasCritical($record)
    {
        return $this->hasRecord($record, Logger::CRITICAL);
    }

    public function hasError($record)
    {
        return $this->hasRecord($record, Logger::ERROR);
    }

    public function hasWarning($record)
    {
        return $this->hasRecord($record, Logger::WARNING);
    }

    public function hasNotice($record)
    {
        return $this->hasRecord($record, Logger::NOTICE);
    }

    public function hasInfo($record)
    {
        return $this->hasRecord($record, Logger::INFO);
    }

    public function hasDebug($record)
    {
        return $this->hasRecord($record, Logger::DEBUG);
    }

    public function hasEmergencyRecords()
    {
        return isset($this->recordsByLevel[Logger::EMERGENCY]);
    }

    public function hasAlertRecords()
    {
        return isset($this->recordsByLevel[Logger::ALERT]);
    }

    public function hasCriticalRecords()
    {
        return isset($this->recordsByLevel[Logger::CRITICAL]);
    }

    public function hasErrorRecords()
    {
        return isset($this->recordsByLevel[Logger::ERROR]);
    }

    public function hasWarningRecords()
    {
        return isset($this->recordsByLevel[Logger::WARNING]);
    }

    public function hasNoticeRecords()
    {
        return isset($this->recordsByLevel[Logger::NOTICE]);
    }

    public function hasInfoRecords()
    {
        return isset($this->recordsByLevel[Logger::INFO]);
    }

    public function hasDebugRecords()
    {
        return isset($this->recordsByLevel[Logger::DEBUG]);
    }

    protected function hasRecord($record, $level)
    {
        if (!isset($this->recordsByLevel[$level])) {
            return false;
        }

        if (is_array($record)) {
            $record = $record['message'];
        }

        foreach ($this->recordsByLevel[$level] as $rec) {
            if ($rec['message'] === $record) {
                return true;
            }
        }

        return false;
    }

    
    protected function write(array $record)
    {
        $this->recordsByLevel[$record['level']][] = $record;
        $this->records[] = $record;
    }
}
}
 



namespace Monolog
{

use Monolog\Handler\HandlerInterface;
use Monolog\Handler\StreamHandler;


class Logger
{
    
    const DEBUG = 100;

    
    const INFO = 200;

    
    const NOTICE = 250;

    
    const WARNING = 300;

    
    const ERROR = 400;

    
    const CRITICAL = 500;

    
    const ALERT = 550;

    
    const EMERGENCY = 600;

    protected static $levels = array(
        100 => 'DEBUG',
        200 => 'INFO',
        250 => 'NOTICE',
        300 => 'WARNING',
        400 => 'ERROR',
        500 => 'CRITICAL',
        550 => 'ALERT',
        600 => 'EMERGENCY',
    );

    
    protected static $timezone;

    protected $name;

    
    protected $handlers = array();

    protected $processors = array();

    
    public function __construct($name)
    {
        $this->name = $name;

        if (!self::$timezone) {
            self::$timezone = new \DateTimeZone(date_default_timezone_get() ?: 'UTC');
        }
    }

    
    public function getName()
    {
        return $this->name;
    }

    
    public function pushHandler(HandlerInterface $handler)
    {
        array_unshift($this->handlers, $handler);
    }

    
    public function popHandler()
    {
        if (!$this->handlers) {
            throw new \LogicException('You tried to pop from an empty handler stack.');
        }

        return array_shift($this->handlers);
    }

    
    public function pushProcessor($callback)
    {
        if (!is_callable($callback)) {
            throw new \InvalidArgumentException('Processors must be valid callables (callback or object with an __invoke method), '.var_export($callback, true).' given');
        }
        array_unshift($this->processors, $callback);
    }

    
    public function popProcessor()
    {
        if (!$this->processors) {
            throw new \LogicException('You tried to pop from an empty processor stack.');
        }

        return array_shift($this->processors);
    }

    
    public function addRecord($level, $message, array $context = array())
    {
        if (!$this->handlers) {
            $this->pushHandler(new StreamHandler('php://stderr', self::DEBUG));
        }
        $record = array(
            'message' => (string) $message,
            'context' => $context,
            'level' => $level,
            'level_name' => self::getLevelName($level),
            'channel' => $this->name,
            'datetime' => \DateTime::createFromFormat('U.u', sprintf('%.6F', microtime(true)))->setTimeZone(self::$timezone),
            'extra' => array(),
        );
                $handlerKey = null;
        foreach ($this->handlers as $key => $handler) {
            if ($handler->isHandling($record)) {
                $handlerKey = $key;
                break;
            }
        }
                if (null === $handlerKey) {
            return false;
        }
                foreach ($this->processors as $processor) {
            $record = call_user_func($processor, $record);
        }
        while (isset($this->handlers[$handlerKey]) &&
            false === $this->handlers[$handlerKey]->handle($record)) {
            $handlerKey++;
        }

        return true;
    }

    
    public function addDebug($message, array $context = array())
    {
        return $this->addRecord(self::DEBUG, $message, $context);
    }

    
    public function addInfo($message, array $context = array())
    {
        return $this->addRecord(self::INFO, $message, $context);
    }

    
    public function addNotice($message, array $context = array())
    {
        return $this->addRecord(self::NOTICE, $message, $context);
    }

    
    public function addWarning($message, array $context = array())
    {
        return $this->addRecord(self::WARNING, $message, $context);
    }

    
    public function addError($message, array $context = array())
    {
        return $this->addRecord(self::ERROR, $message, $context);
    }

    
    public function addCritical($message, array $context = array())
    {
        return $this->addRecord(self::CRITICAL, $message, $context);
    }

    
    public function addAlert($message, array $context = array())
    {
        return $this->addRecord(self::ALERT, $message, $context);
    }

    
    public function addEmergency($message, array $context = array())
    {
      return $this->addRecord(self::EMERGENCY, $message, $context);
    }

    
    public static function getLevelName($level)
    {
        return self::$levels[$level];
    }

    
    public function isHandling($level)
    {
        $record = array(
            'message' => '',
            'context' => array(),
            'level' => $level,
            'level_name' => self::getLevelName($level),
            'channel' => $this->name,
            'datetime' => new \DateTime(),
            'extra' => array(),
        );

        foreach ($this->handlers as $key => $handler) {
            if ($handler->isHandling($record)) {
                return true;
            }
        }

        return false;
    }

    
    public function debug($message, array $context = array())
    {
        return $this->addRecord(self::DEBUG, $message, $context);
    }

    
    public function info($message, array $context = array())
    {
        return $this->addRecord(self::INFO, $message, $context);
    }

    
    public function notice($message, array $context = array())
    {
        return $this->addRecord(self::NOTICE, $message, $context);
    }

    
    public function warn($message, array $context = array())
    {
        return $this->addRecord(self::WARNING, $message, $context);
    }

    
    public function err($message, array $context = array())
    {
        return $this->addRecord(self::ERROR, $message, $context);
    }

    
    public function crit($message, array $context = array())
    {
        return $this->addRecord(self::CRITICAL, $message, $context);
    }

    
    public function alert($message, array $context = array())
    {
        return $this->addRecord(self::ALERT, $message, $context);
    }

    
    public function emerg($message, array $context = array())
    {
        return $this->addRecord(self::EMERGENCY, $message, $context);
    }
}
}
 



namespace Sonata\AdminBundle\Admin
{

use Sonata\AdminBundle\Admin\Pool;
use Sonata\AdminBundle\Admin\FieldDescriptionInterface;
use Sonata\AdminBundle\Builder\FormContractorInterface;
use Sonata\AdminBundle\Builder\ListBuilderInterface;
use Sonata\AdminBundle\Builder\DatagridBuilderInterface;
use Sonata\AdminBundle\Security\Handler\SecurityHandlerInterface;
use Sonata\AdminBundle\Builder\RouteBuilderInterface;
use Sonata\AdminBundle\Translator\LabelTranslatorStrategyInterface;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Route\RouteGeneratorInterface;

use Knp\Menu\FactoryInterface as MenuFactoryInterface;

use Symfony\Component\Validator\ValidatorInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Request;

interface AdminInterface
{
    
    public function setFormContractor(FormContractorInterface $formContractor);

    
    public function setListBuilder(ListBuilderInterface $listBuilder);

    
    public function setDatagridBuilder(DatagridBuilderInterface $datagridBuilder);

    
    public function setTranslator(TranslatorInterface $translator);

    
    public function setRequest(Request $request);

    
    public function setConfigurationPool(Pool $pool);

    
    public function setRouteGenerator(RouteGeneratorInterface $routeGenerator);

    
    public function getClass();

    
    public function attachAdminClass(FieldDescriptionInterface $fieldDescription);

    
    public function getDatagrid();

    
    public function generateUrl($name, array $parameters = array(), $absolute = false);

    
    public function getModelManager();

    
    public function getManagerType();

    
    public function createQuery($context = 'list');

    
    public function getFormBuilder();

    
    public function getFormFieldDescription($name);

    
    public function getRequest();

    
    public function getCode();

    
    public function getSecurityInformation();

    
    public function setParentFieldDescription(FieldDescriptionInterface $parentFieldDescription);

    
    public function trans($id, array $parameters = array(), $domain = null, $locale = null);

    
    public function getRouterIdParameter();

    
    public function addShowFieldDescription($name, FieldDescriptionInterface $fieldDescription);

    
    public function addListFieldDescription($name, FieldDescriptionInterface $fieldDescription);

    
    public function addFilterFieldDescription($name, FieldDescriptionInterface $fieldDescription);

    
    public function getList();

    
    public function setSecurityHandler(SecurityHandlerInterface $securityHandler);

    
    public function getSecurityHandler();

    
    public function isGranted($name, $object = null);

    
    public function getUrlsafeIdentifier($entity);

    
    public function getNormalizedIdentifier($entity);

    
    public function id($entity);

    
    public function setValidator(ValidatorInterface $validator);

    
    public function getValidator();

    
    public function getShow();

    
    public function setFormTheme(array $formTheme);

    
    public function getFormTheme();

    
    public function setFilterTheme(array $filterTheme);

    
    public function getFilterTheme();

    
    public function addExtension(AdminExtensionInterface $extension);

    
    public function getExtensions();

    
    public function setMenuFactory(MenuFactoryInterface $menuFactory);

    
    public function getMenuFactory();

    
    public function setRouteBuilder(RouteBuilderInterface $routeBuilder);

    
    public function getRouteBuilder();

    
    public function toString($object);

    
    public function setLabelTranslatorStrategy(LabelTranslatorStrategyInterface $labelTranslatorStrategy);

    
    public function getLabelTranslatorStrategy();

    
    public function addChild(AdminInterface $child);

    
    public function hasChild($code);

    
    public function getChildren();

    
    public function getChild($code);

    
    public function getNewInstance();

    
    public function setUniqid($uniqId);

    
    public function getObject($id);

    
    public function setSubject($subject);

    
    public function getSubject();

    
    public function getListFieldDescription($name);

    
    public function configure();

    
    public function update($object);

    
    public function create($object);

    
    public function delete($object);

    
    public function preUpdate($object);

    
    public function postUpdate($object);

    
    public function prePersist($object);

    
    public function postPersist($object);

    
    public function preRemove($object);

    
    public function postRemove($object);

    
    public function hasSubject();

    
    public function validate(ErrorElement $errorElement, $object);

    
    public function showIn($context);

    
    public function createObjectSecurity($object);

    
    public function getRoute($name);

    
    public function getParent();

    
    public function setParent(AdminInterface $admin);

    
    public function getTemplate($name);
}
}
 



namespace Symfony\Component\Security\Acl\Model
{


interface DomainObjectInterface
{
    
    public function getObjectIdentifier();
}
}
 



namespace Sonata\AdminBundle\Admin
{

use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Util\PropertyPath;
use Symfony\Component\Validator\ValidatorInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Acl\Model\DomainObjectInterface;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Show\ShowMapper;

use Sonata\AdminBundle\Admin\Pool;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Validator\Constraints\InlineConstraint;

use Sonata\AdminBundle\Translator\LabelTranslatorStrategyInterface;
use Sonata\AdminBundle\Builder\FormContractorInterface;
use Sonata\AdminBundle\Builder\ListBuilderInterface;
use Sonata\AdminBundle\Builder\DatagridBuilderInterface;
use Sonata\AdminBundle\Builder\ShowBuilderInterface;
use Sonata\AdminBundle\Builder\RouteBuilderInterface;
use Sonata\AdminBundle\Route\RouteGeneratorInterface;

use Sonata\AdminBundle\Security\Handler\SecurityHandlerInterface;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Model\ModelManagerInterface;

use Knp\Menu\FactoryInterface as MenuFactoryInterface;
use Knp\Menu\ItemInterface as MenuItemInterface;

abstract class Admin implements AdminInterface, DomainObjectInterface
{
    const CONTEXT_MENU       = 'menu';
    const CONTEXT_DASHBOARD  = 'dashboard';

    
    private $class;

    
    private $subClasses = array();

    
    private $list;

    
    protected $listFieldDescriptions = array();

    private $show;

    
    protected $showFieldDescriptions = array();

    
    private $form;

    
    protected $formFieldDescriptions = array();

    
    private $filter;

    
    protected $filterFieldDescriptions = array();

    
    protected $maxPerPage = 25;

    
    protected $maxPageLinks = 25;

    
    protected $baseRouteName;

    
    protected $baseRoutePattern;

    
    protected $baseControllerName;

    
    private $formGroups = false;

    
    private $showGroups = false;

    
    protected $classnameLabel;

    
    protected $translationDomain = 'messages';

    
    protected $formOptions = array();

    
    protected $datagridValues = array(
        '_page'       => 1,
        '_per_page'   => 25,
    );

    
    protected $perPageOptions = array(15, 25, 50, 100, 150, 200);

    
    protected $code;

    
    protected $label;

    
    protected $persistFilters = false;

    
    protected $routes;

    
    protected $subject;

    
    protected $children = array();

    
    protected $parent = null;

    
    protected $baseCodeRoute = '';

    
    protected $parentAssociationMapping = null;

    
    protected $parentFieldDescription;

    
    protected $currentChild = false;

    
    protected $uniqid;

    
    protected $modelManager;

    
    private $managerType;

    
    protected $request;

    
    protected $translator;

    
    protected $formContractor;

    
    protected $listBuilder;

    
    protected $showBuilder;

    
    protected $datagridBuilder;

    
    protected $routeBuilder;

    
    protected $datagrid;

    
    protected $routeGenerator;

    
    protected $breadcrumbs = array();

    protected $securityHandler = null;

    
    protected $validator = null;

    
    protected $configurationPool;

    protected $menu;

    
    protected $menuFactory;

    protected $loaded = array(
        'view_fields'   => false,
        'view_groups'   => false,
        'routes'        => false,
        'side_menu'     => false,
    );

    protected $formTheme = array();

    protected $filterTheme = array();

    protected $templates  = array();

    protected $extensions = array();

    protected $labelTranslatorStrategy;

    
    protected $supportsPreviewMode = false;

    
    protected $securityInformation = array();

    
    protected function configureFormFields(FormMapper $form)
    {

    }

    
    protected function configureListFields(ListMapper $list)
    {

    }

    
    protected function configureDatagridFilters(DatagridMapper $filter)
    {

    }

    
    protected function configureShowField(ShowMapper $show)
    {

    }

    
    protected function configureShowFields(ShowMapper $filter)
    {

    }

    
    protected function configureRoutes(RouteCollection $collection)
    {

    }

    
    protected function configureSideMenu(MenuItemInterface $menu, $action, AdminInterface $childAdmin = null)
    {

    }

    
    public function getExportFormats()
    {
        return array(
            'json', 'xml', 'csv', 'xls'
        );
    }

    
    public function getExportFields()
    {
        return $this->getModelManager()->getExportFields($this->getClass());
    }

    
    public function getDataSourceIterator()
    {
        $datagrid = $this->getDatagrid();
        $datagrid->buildPager();

        return $this->getModelManager()->getDataSourceIterator($datagrid, $this->getExportFields());
    }

    
    public function validate(ErrorElement $errorElement, $object)
    {

    }

    
    public function __construct($code, $class, $baseControllerName)
    {
        $this->code                 = $code;
        $this->class                = $class;
        $this->baseControllerName   = $baseControllerName;

        $this->predefinePerPageOptions();
        $this->datagridValues['_per_page'] = $this->maxPerPage;
    }

    
    public function initialize()
    {
        $this->uniqid = "s".uniqid();

        if (!$this->classnameLabel) {
            $this->classnameLabel = substr($this->getClass(), strrpos($this->getClass(), '\\') + 1);
        }

        $this->baseCodeRoute = $this->getCode();

        $this->configure();
    }

    
    public function configure()
    {

    }

    
    public function update($object)
    {
        $this->preUpdate($object);
        $this->getModelManager()->update($object);
        $this->postUpdate($object);
    }

    
    public function create($object)
    {
        $this->prePersist($object);
        $this->getModelManager()->create($object);
        $this->postPersist($object);
        $this->createObjectSecurity($object);
    }

    
    public function delete($object)
    {
        $this->preRemove($object);
        $this->getSecurityHandler()->deleteObjectSecurity($this, $object);
        $this->getModelManager()->delete($object);
        $this->postRemove($object);
    }

    
    public function preUpdate($object)
    {

    }

    
    public function postUpdate($object)
    {

    }

    
    public function prePersist($object)
    {

    }

    
    public function postPersist($object)
    {

    }

    
    public function preRemove($object)
    {

    }

    
    public function postRemove($object)
    {

    }

    
    protected function buildShow()
    {
        if ($this->show) {
            return;
        }

        $this->show = new FieldDescriptionCollection();
        $mapper = new ShowMapper($this->showBuilder, $this->show, $this);

        $this->configureShowField($mapper);         $this->configureShowFields($mapper);

        foreach ($this->getExtensions() as $extension) {
            $extension->configureShowFields($mapper);
        }
    }

    
    protected function buildList()
    {
        if ($this->list) {
            return;
        }

        $this->list = $this->getListBuilder()->getBaseList();

        $mapper = new ListMapper($this->getListBuilder(), $this->list, $this);

        if (count($this->getBatchActions()) > 0) {
            $fieldDescription = $this->getModelManager()->getNewFieldDescriptionInstance($this->getClass(), 'batch', array(
                'label'    => 'batch',
                'code'     => '_batch',
                'sortable' => false
            ));

            $fieldDescription->setAdmin($this);
            $fieldDescription->setTemplate($this->getTemplate('batch'));

            $mapper->add($fieldDescription, 'batch');
        }

        $this->configureListFields($mapper);

        foreach ($this->getExtensions() as $extension) {
            $extension->configureListFields($mapper);
        }
    }

    
    public function getFilterParameters()
    {
        $parameters = array();

                if ($this->hasRequest()) {
            $filters = $this->request->query->get('filter', array());

                        if ($this->persistFilters) {
                if ($filters == array() && $this->request->query->get('filters') != 'reset') {
                    $filters = $this->request->getSession()->get($this->getCode().'.filter.parameters', array());
                } else {
                    $this->request->getSession()->set($this->getCode().'.filter.parameters', $filters);
                }
            }

            $parameters = array_merge(
                $this->getModelManager()->getDefaultSortValues($this->getClass()),
                $this->datagridValues,
                $filters
            );

            if (!$this->determinedPerPageValue($parameters['_per_page'])) {
                $parameters['_per_page'] = $this->maxPerPage;
            }

                        if ($this->isChild() && $this->getParentAssociationMapping()) {
                $parameters[$this->getParentAssociationMapping()] = array('value' => $this->request->get($this->getParent()->getIdParameter()));
            }
        }

        return $parameters;
    }

    
    public function buildDatagrid()
    {
        if ($this->datagrid) {
            return;
        }

        $filterParameters = $this->getFilterParameters();

                if (isset($filterParameters['_sort_by']) && is_string($filterParameters['_sort_by'])) {
            if ($this->hasListFieldDescription($filterParameters['_sort_by'])) {
                $filterParameters['_sort_by'] = $this->getListFieldDescription($filterParameters['_sort_by']);
            } else {
                $filterParameters['_sort_by'] = $this->getModelManager()->getNewFieldDescriptionInstance(
                    $this->getClass(),
                    $filterParameters['_sort_by'],
                    array()
                );

                $this->getListBuilder()->buildField(null, $filterParameters['_sort_by'], $this);
            }
        }

                $this->datagrid = $this->getDatagridBuilder()->getBaseDatagrid($this, $filterParameters);

        $this->datagrid->getPager()->setMaxPageLinks($this->maxPageLinks);

        $mapper = new DatagridMapper($this->getDatagridBuilder(), $this->datagrid, $this);

                $this->configureDatagridFilters($mapper);

                if ($this->isChild() && $this->getParentAssociationMapping() && !$mapper->has($this->getParentAssociationMapping())) {
            $mapper->add($this->getParentAssociationMapping(), null, array(
                'field_type' => 'sonata_type_model_reference',
                'field_options' => array(
                    'model_manager' => $this->getModelManager()
                ),
                'operator_type' => 'hidden'
            ));
        }

        foreach ($this->getExtensions() as $extension) {
            $extension->configureDatagridFilters($mapper);
        }
    }

    
    public function getParentAssociationMapping()
    {
        return $this->parentAssociationMapping;
    }

    
    protected function buildForm()
    {
        if ($this->form) {
            return;
        }

                        if ($this->isChild() && $this->getParentAssociationMapping()) {
            $parent = $this->getParent()->getObject($this->request->get($this->getParent()->getIdParameter()));

            $propertyPath = new PropertyPath($this->getParentAssociationMapping());

            $object = $this->getSubject();

            $propertyPath->setValue($object, $parent);
        }

        $this->form = $this->getFormBuilder()->getForm();
    }

    
    public function getBaseRoutePattern()
    {
        if (!$this->baseRoutePattern) {
            preg_match('@([A-Za-z0-9]*)\\\([A-Za-z0-9]*)Bundle\\\(Entity|Document|Model)\\\(.*)@', $this->getClass(), $matches);

            if (!$matches) {
                throw new \RuntimeException(sprintf('Please define a default `baseRoutePattern` value for the admin class `%s`', get_class($this)));
            }

            if ($this->isChild()) {                 $this->baseRoutePattern = sprintf('%s/{id}/%s',
                    $this->getParent()->getBaseRoutePattern(),
                    $this->urlize($matches[4], '-')
                );
            } else {

                $this->baseRoutePattern = sprintf('/%s/%s/%s',
                    $this->urlize($matches[1], '-'),
                    $this->urlize($matches[2], '-'),
                    $this->urlize($matches[4], '-')
                );
            }
        }

        return $this->baseRoutePattern;
    }

    
    public function getBaseRouteName()
    {
        if (!$this->baseRouteName) {
            preg_match('@([A-Za-z0-9]*)\\\([A-Za-z0-9]*)Bundle\\\(Entity|Document|Model)\\\(.*)@', $this->getClass(), $matches);

            if (!$matches) {
                throw new \RuntimeException(sprintf('Please define a default `baseRouteName` value for the admin class `%s`', get_class($this)));
            }

            if ($this->isChild()) {                 $this->baseRouteName = sprintf('%s_%s',
                    $this->getParent()->getBaseRouteName(),
                    $this->urlize($matches[4])
                );
            } else {

                $this->baseRouteName = sprintf('admin_%s_%s_%s',
                    $this->urlize($matches[1]),
                    $this->urlize($matches[2]),
                    $this->urlize($matches[4])
                );
            }
        }

        return $this->baseRouteName;
    }

    
    public function urlize($word, $sep = '_')
    {
        return strtolower(preg_replace('/[^a-z0-9_]/i', $sep.'$1', $word));
    }

    
    public function getClass()
    {
        return $this->class;
    }

    
    public function getSubClasses()
    {
        return $this->subClasses;
    }

    
    public function setSubClasses(array $subClasses)
    {
        $this->subClasses = $subClasses;
    }

    
    protected function getSubClass($name)
    {
        if ($this->hasSubClass($name)) {
            return $this->subClasses[$name];
        }

        return null;
    }

    
    public function hasSubClass($name)
    {
        return isset($this->subClasses[$name]);
    }

    
    public function hasActiveSubClass()
    {
        if ($this->request) {
            return null !== $this->getRequest()->get('subclass');
        }

        return false;
    }

    
    public function getActiveSubClass()
    {
        if (!$this->hasActiveSubClass()) {
            return null;
        }

        $subClass = $this->getRequest()->get('subclass');

        return $this->getSubClass($subClass);
    }

    
    public function getBatchActions()
    {
        $actions = array();

        if ($this->hasRoute('delete') && $this->isGranted('DELETE')) {
            $actions['delete'] = array(
                'label'            => $this->trans('action_delete', array(), 'SonataAdminBundle'),
                'ask_confirmation' => true,             );
        }

        return $actions;
    }

    
    public function getRoutes()
    {
        $this->buildRoutes();

        return $this->routes;
    }

    
    public function getRouterIdParameter()
    {
        return $this->isChild() ? '{childId}' : '{id}';
    }

    
    public function getIdParameter()
    {
        return $this->isChild() ? 'childId' : 'id';
    }

    
    public function buildRoutes()
    {
        if ($this->loaded['routes']) {
            return;
        }

        $this->loaded['routes'] = true;

        $this->routes = new RouteCollection(
            $this->getBaseCodeRoute(),
            $this->getBaseRouteName(),
            $this->getBaseRoutePattern(),
            $this->getBaseControllerName()
        );

        $this->routeBuilder->build($this, $this->routes);

        $this->configureRoutes($this->routes);

        foreach ($this->getExtensions() as $extension) {
            $extension->configureRoutes($this, $this->routes);
        }
    }

    
    public function getRoute($name)
    {
        $this->buildRoutes();

        if (!$this->routes->has($name)) {
            return false;
        }

        return $this->routes->get($name);
    }

    
    public function hasRoute($name)
    {
        $this->buildRoutes();

        if (
            ! $this->isChild()
            && strpos($name, '.') !== false
            && strpos($name, $this->getBaseCodeRoute() . '|') !== 0
            && strpos($name, $this->getBaseCodeRoute() . '.') !== 0
        ) {
            $name = $this->getCode() . '|' . $name;
        }

        return $this->routes->has($name);
    }

    
    public function generateObjectUrl($name, $object, array $parameters = array(), $absolute = false)
    {
        $parameters['id'] = $this->getUrlsafeIdentifier($object);

        return $this->generateUrl($name, $parameters, $absolute);
    }

    
    public function generateUrl($name, array $parameters = array(), $absolute = false)
    {
        return $this->routeGenerator->generateUrl($this, $name, $parameters, $absolute);
    }

    
    public function setTemplates(array $templates)
    {
        $this->templates = $templates;
    }

    
    public function setTemplate($name, $template)
    {
        $this->templates[$name] = $template;
    }

    
    public function getTemplates()
    {
        return $this->templates;
    }

    
    public function getTemplate($name)
    {
        if (isset($this->templates[$name])) {
            return $this->templates[$name];
        }

        return null;
    }

    
    public function getNewInstance()
    {
        return $this->getModelManager()->getModelInstance($this->getActiveSubClass() ?: $this->getClass());
    }

    
    public function getFormBuilder()
    {
        $this->formOptions['data_class'] = $this->getActiveSubClass() ?: $this->getClass();

        $formBuilder = $this->getFormContractor()->getFormBuilder(
            $this->getUniqid(),
            $this->formOptions
        );

        $this->defineFormBuilder($formBuilder);

        return $formBuilder;
    }

    
    public function defineFormBuilder(FormBuilder $formBuilder)
    {
        $mapper = new FormMapper($this->getFormContractor(), $formBuilder, $this);

        $this->configureFormFields($mapper);

        foreach ($this->getExtensions() as $extension) {
            $extension->configureFormFields($mapper);
        }

        $this->attachInlineValidator();
    }

    
    protected function attachInlineValidator()
    {
        $admin = $this;

                $metadata = $this->validator->getMetadataFactory()->getClassMetadata($this->getClass());

        $metadata->addConstraint(new InlineConstraint(array(
            'service' => $this,
            'method'  => function(ErrorElement $errorElement, $object) use ($admin) {
                

                                                if ($admin->hasSubject() && spl_object_hash($object) !== spl_object_hash($admin->getSubject())) {
                    return;
                }

                $admin->validate($errorElement, $object);

                foreach ($admin->getExtensions() as $extension) {
                    $extension->validate($admin, $errorElement, $object);
                }
            }
        )));
    }

    
    public function attachAdminClass(FieldDescriptionInterface $fieldDescription)
    {
        $pool = $this->getConfigurationPool();

        $adminCode = $fieldDescription->getOption('admin_code');

        if ($adminCode !== null) {
            $admin = $pool->getAdminByAdminCode($adminCode);
        } else {
            $admin = $pool->getAdminByClass($fieldDescription->getTargetEntity());
        }

        if (!$admin) {
            return;
        }

        if ($this->hasRequest()) {
            $admin->setRequest($this->getRequest());
        }

        $fieldDescription->setAssociationAdmin($admin);
    }

    
    public function getObject($id)
    {
        return $this->getModelManager()->find($this->getClass(), $id);
    }

    
    public function getForm()
    {
        $this->buildForm();

        return $this->form;
    }

    
    public function getList()
    {
        $this->buildList();

        return $this->list;
    }

    
    public function createQuery($context = 'list')
    {
        $query = $this->getModelManager()->createQuery($this->class);

        foreach ($this->extensions as $extension) {
            $extension->configureQuery($this, $query, $context);
        }

        return $query;
    }

    
    public function getDatagrid()
    {
        $this->buildDatagrid();

        return $this->datagrid;
    }

    
    public function buildSideMenu($action, AdminInterface $childAdmin = null)
    {
        if ($this->loaded['side_menu']) {
            return;
        }

        $this->loaded['side_menu'] = true;

        $menu = $this->menuFactory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav nav-list');
        $menu->setCurrentUri($this->getRequest()->getBaseUrl().$this->getRequest()->getPathInfo());

        $this->configureSideMenu($menu, $action, $childAdmin);

        foreach ($this->getExtensions() as $extension) {
            $extension->configureSideMenu($this, $menu, $action, $childAdmin);
        }

        $this->menu = $menu;
    }

    
    public function getSideMenu($action, AdminInterface $childAdmin = null)
    {
        if ($this->isChild()) {
            return $this->getParent()->getSideMenu($action, $this);
        }

        $this->buildSideMenu($action, $childAdmin);

        return $this->menu;
    }

    
    public function getRootCode()
    {
        return $this->getRoot()->getCode();
    }

    
    public function getRoot()
    {
        $parentFieldDescription = $this->getParentFieldDescription();

        if (!$parentFieldDescription) {
            return $this;
        }

        return $parentFieldDescription->getAdmin()->getRoot();
    }

    
    public function setBaseControllerName($baseControllerName)
    {
        $this->baseControllerName = $baseControllerName;
    }

    
    public function getBaseControllerName()
    {
        return $this->baseControllerName;
    }

    
    public function setLabel($label)
    {
        $this->label = $label;
    }

    
    public function getLabel()
    {
        return $this->label;
    }

    
    public function setPersistFilters($persist)
    {
        $this->persistFilters = $persist;
    }

    
    public function setMaxPerPage($maxPerPage)
    {
        $this->maxPerPage = $maxPerPage;
    }

    
    public function getMaxPerPage()
    {
        return $this->maxPerPage;
    }

    
    public function setMaxPageLinks($maxPageLinks)
    {
        $this->maxPageLinks = $maxPageLinks;
    }

    
    public function getMaxPageLinks()
    {
        return $this->maxPageLinks;
    }

    
    public function getFormGroups()
    {
        return $this->formGroups;
    }

    
    public function setFormGroups(array $formGroups)
    {
        $this->formGroups = $formGroups;
    }

    
    public function reorderFormGroup($group, array $keys)
    {
        $formGroups = $this->getFormGroups();
        $formGroups[$group]['fields'] = array_merge(array_flip($keys), $formGroups[$group]['fields']);
        $this->setFormGroups($formGroups);
    }

    
    public function getShowGroups()
    {
        return $this->showGroups;
    }

    
    public function setShowGroups(array $showGroups)
    {
        $this->showGroups = $showGroups;
    }

    
    public function reorderShowGroup($group, array $keys)
    {
        $showGroups                   = $this->getShowGroups();
        $showGroups[$group]['fields'] = array_merge(array_flip($keys), $showGroups[$group]['fields']);
        $this->setShowGroups($showGroups);
    }

    
    public function setParentFieldDescription(FieldDescriptionInterface $parentFieldDescription)
    {
        $this->parentFieldDescription = $parentFieldDescription;
    }

    
    public function getParentFieldDescription()
    {
        return $this->parentFieldDescription;
    }

    
    public function hasParentFieldDescription()
    {
        return $this->parentFieldDescription instanceof FieldDescriptionInterface;
    }

    
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    
    public function getSubject()
    {
        if ($this->subject === null && $this->request) {
            $id = $this->request->get($this->getIdParameter());
            if (!preg_match('#^[0-9A-Fa-f]+$#', $id)) {
                $this->subject = false;
            } else {
                $this->subject = $this->getModelManager()->find($this->getClass(), $id);
            }
        }

        return $this->subject;
    }

    
    public function hasSubject()
    {
        return $this->subject != null;
    }

    
    public function getFormFieldDescriptions()
    {
        $this->buildForm();

        return $this->formFieldDescriptions;
    }

    
    public function getFormFieldDescription($name)
    {
        return $this->hasFormFieldDescription($name) ? $this->formFieldDescriptions[$name] : null;
    }

    
    public function hasFormFieldDescription($name)
    {
        return array_key_exists($name, $this->formFieldDescriptions) ? true : false;
    }

    
    public function addFormFieldDescription($name, FieldDescriptionInterface $fieldDescription)
    {
        $this->formFieldDescriptions[$name] = $fieldDescription;
    }

    
    public function removeFormFieldDescription($name)
    {
        unset($this->formFieldDescriptions[$name]);
    }

    
    public function getShowFieldDescriptions()
    {
        $this->buildShow();

        return $this->showFieldDescriptions;
    }

    
    public function getShowFieldDescription($name)
    {
        $this->buildShow();

        return $this->hasShowFieldDescription($name) ? $this->showFieldDescriptions[$name] : null;
    }

    
    public function hasShowFieldDescription($name)
    {
        return array_key_exists($name, $this->showFieldDescriptions);
    }

    
    public function addShowFieldDescription($name, FieldDescriptionInterface $fieldDescription)
    {
        $this->showFieldDescriptions[$name] = $fieldDescription;
    }

    
    public function removeShowFieldDescription($name)
    {
        unset($this->showFieldDescriptions[$name]);
    }

    
    public function getListFieldDescriptions()
    {
        $this->buildList();

        return $this->listFieldDescriptions;
    }

    
    public function getListFieldDescription($name)
    {
        return $this->hasListFieldDescription($name) ? $this->listFieldDescriptions[$name] : null;
    }

    
    public function hasListFieldDescription($name)
    {
        $this->buildList();

        return array_key_exists($name, $this->listFieldDescriptions) ? true : false;
    }

    
    public function addListFieldDescription($name, FieldDescriptionInterface $fieldDescription)
    {
        $this->listFieldDescriptions[$name] = $fieldDescription;
    }

    
    public function removeListFieldDescription($name)
    {
        unset($this->listFieldDescriptions[$name]);
    }

    
    public function getFilterFieldDescription($name)
    {
        return $this->hasFilterFieldDescription($name) ? $this->filterFieldDescriptions[$name] : null;
    }

    
    public function hasFilterFieldDescription($name)
    {
        return array_key_exists($name, $this->filterFieldDescriptions) ? true : false;
    }

    
    public function addFilterFieldDescription($name, FieldDescriptionInterface $fieldDescription)
    {
        $this->filterFieldDescriptions[$name] = $fieldDescription;
    }

    
    public function removeFilterFieldDescription($name)
    {
        unset($this->filterFieldDescriptions[$name]);
    }

    
    public function getFilterFieldDescriptions()
    {
        $this->buildDatagrid();

        return $this->filterFieldDescriptions;
    }

    
    public function addChild(AdminInterface $child)
    {
        $this->children[$child->getCode()] = $child;

        $child->setBaseCodeRoute($this->getCode().'|'.$child->getCode());
        $child->setParent($this);
    }

    
    public function hasChild($code)
    {
        return isset($this->children[$code]);
    }

    
    public function getChildren()
    {
        return $this->children;
    }

    
    public function getChild($code)
    {
        return $this->hasChild($code) ? $this->children[$code] : null;
    }

    
    public function setParent(AdminInterface $parent)
    {
        $this->parent = $parent;
    }

    
    public function getParent()
    {
        return $this->parent;
    }

    
    public function isChild()
    {
        return $this->parent instanceof AdminInterface;
    }

    
    public function hasChildren()
    {
        return count($this->children) > 0;
    }

    
    public function setUniqid($uniqid)
    {
        $this->uniqid = $uniqid;
    }

    
    public function getUniqid()
    {
        return $this->uniqid;
    }

    
    public function getClassnameLabel()
    {
        return $this->classnameLabel;
    }

    
    public function getPersistentParameters()
    {
        return array();
    }

    
    public function getPersistentParameter($name)
    {
        $parameters = $this->getPersistentParameters();

        return isset($parameters[$name]) ? $parameters[$name] : null;
    }

    
    public function getBreadcrumbs($action)
    {
        if ($this->isChild()) {
            return $this->getParent()->getBreadcrumbs($action);
        }

        return $this->buildBreadcrumbs($action);
    }

    
    public function buildBreadcrumbs($action, MenuItemInterface $menu = null)
    {
        if (isset($this->breadcrumbs[$action])) {
            return $this->breadcrumbs[$action];
        }

        if (!$menu) {
            $menu = $this->menuFactory->createItem('root');
        }

        $child = $menu->addChild(
            $this->trans($this->getLabelTranslatorStrategy()->getLabel('dashboard', 'breadcrumb', 'link'), array(), 'SonataAdminBundle'),
            array('uri' => $this->routeGenerator->generate('sonata_admin_dashboard'))
        );

        $child = $child->addChild(
            $this->trans($this->getLabelTranslatorStrategy()->getLabel(sprintf('%s_list', $this->getClassnameLabel()), 'breadcrumb', 'link')),
            array('uri' => $this->hasRoute('list') && $this->isGranted('LIST') ? $this->generateUrl('list') : null)
        );

        $childAdmin = $this->getCurrentChildAdmin();

        if ($childAdmin) {
            $id = $this->request->get($this->getIdParameter());

            $child = $child->addChild(
                $this->toString($this->getSubject()),
                array('uri' => $this->hasRoute('edit') && $this->isGranted('EDIT') ? $this->generateUrl('edit', array('id' => $id)) : null)
            );

            return $childAdmin->buildBreadcrumbs($action, $child);

        } elseif ($this->isChild()) {
            if ($action != 'list') {
                $menu = $menu->addChild(
                    $this->trans($this->getLabelTranslatorStrategy()->getLabel(sprintf('%s_list', $this->getClassnameLabel()), 'breadcrumb', 'link')),
                    array('uri' => $this->hasRoute('list') && $this->isGranted('LIST') ? $this->generateUrl('list') : null)
                );
            }

            if ($action != 'create' && $this->hasSubject()) {
                $breadcrumbs = $menu->getBreadcrumbsArray($this->toString($this->getSubject()));
            } else {
                $breadcrumbs = $menu->getBreadcrumbsArray(
                    $this->trans($this->getLabelTranslatorStrategy()->getLabel(sprintf('%s_%s', $this->getClassnameLabel(), $action), 'breadcrumb', 'link'))
                );
            }
        } elseif ($action != 'list' && $this->hasSubject()) {
            $breadcrumbs = $child->getBreadcrumbsArray(
                $this->toString($this->getSubject())
            );
        } elseif ($action != 'list') {
            $breadcrumbs = $child->getBreadcrumbsArray(
                $this->trans($this->getLabelTranslatorStrategy()->getLabel(sprintf('%s_%s', $this->getClassnameLabel(), $action), 'breadcrumb', 'link'))
            );
        } else {
            $breadcrumbs = $child->getBreadcrumbsArray();
        }

                array_shift($breadcrumbs);

        return $this->breadcrumbs[$action] = $breadcrumbs;
    }

    
    public function setCurrentChild($currentChild)
    {
        $this->currentChild = $currentChild;
    }

    
    public function getCurrentChild()
    {
        return $this->currentChild;
    }

    
    public function getCurrentChildAdmin()
    {
        foreach ($this->children as $children) {
            if ($children->getCurrentChild()) {
                return $children;
            }
        }

        return null;
    }

    
    public function trans($id, array $parameters = array(), $domain = null, $locale = null)
    {
        $domain = $domain ?: $this->translationDomain;

        if (!$this->translator) {
            return $id;
        }

        return $this->translator->trans($id, $parameters, $domain, $locale);
    }

    
    public function transChoice($id, $count, array $parameters = array(), $domain = null, $locale = null)
    {
        $domain = $domain ?: $this->translationDomain;

        if (!$this->translator) {
            return $id;
        }

        return $this->translator->transChoice($id, $count, $parameters, $domain, $locale);
    }

    
    public function setTranslationDomain($translationDomain)
    {
        $this->translationDomain = $translationDomain;
    }

    
    public function getTranslationDomain()
    {
        return $this->translationDomain;
    }

    
    public function setTranslator(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    
    public function getTranslator()
    {
        return $this->translator;
    }

    
    public function setRequest(Request $request)
    {
        $this->request = $request;

        foreach ($this->getChildren() as $children) {
            $children->setRequest($request);
        }
    }

    
    public function getRequest()
    {
        if (!$this->request) {
            throw new \RuntimeException('The Request object has not been set');
        }

        return $this->request;
    }

    
    public function hasRequest()
    {
        return $this->request !== null;
    }

    
    public function setFormContractor(FormContractorInterface $formBuilder)
    {
        $this->formContractor = $formBuilder;
    }

    
    public function getFormContractor()
    {
        return $this->formContractor;
    }

    
    public function setDatagridBuilder(DatagridBuilderInterface $datagridBuilder)
    {
        $this->datagridBuilder = $datagridBuilder;
    }

    
    public function getDatagridBuilder()
    {
        return $this->datagridBuilder;
    }

    
    public function setListBuilder(ListBuilderInterface $listBuilder)
    {
        $this->listBuilder = $listBuilder;
    }

    
    public function getListBuilder()
    {
        return $this->listBuilder;
    }

    
    public function setShowBuilder(ShowBuilderInterface $showBuilder)
    {
        $this->showBuilder = $showBuilder;
    }

    
    public function getShowBuilder()
    {
        return $this->showBuilder;
    }

    
    public function setConfigurationPool(Pool $configurationPool)
    {
        $this->configurationPool = $configurationPool;
    }

    
    public function getConfigurationPool()
    {
        return $this->configurationPool;
    }

    
    public function setRouteGenerator(RouteGeneratorInterface $routeGenerator)
    {
        $this->routeGenerator = $routeGenerator;
    }

    
    public function getRouteGenerator()
    {
        return $this->routeGenerator;
    }

    
    public function getCode()
    {
        return $this->code;
    }

    
    public function setBaseCodeRoute($baseCodeRoute)
    {
        $this->baseCodeRoute = $baseCodeRoute;
    }

    
    public function getBaseCodeRoute()
    {
        return $this->baseCodeRoute;
    }

    
    public function getModelManager()
    {
        return $this->modelManager;
    }

    
    public function setModelManager(ModelManagerInterface $modelManager)
    {
        $this->modelManager = $modelManager;
    }

    
    public function getManagerType()
    {
        return $this->managerType;
    }

    
    public function setManagerType($type)
    {
        $this->managerType = $type;
    }

    
    public function getObjectIdentifier()
    {
        return $this->getCode();
    }

    
    public function setSecurityInformation(array $information)
    {
        $this->securityInformation = $information;
    }

    
    public function getSecurityInformation()
    {
        return $this->securityInformation;
    }

    
    public function getPermissionsShow($context)
    {
        switch ($context) {
            case self::CONTEXT_DASHBOARD:
            case self::CONTEXT_MENU:
            default:
                return array('LIST');
        }
    }

    
    public function showIn($context)
    {
        switch ($context) {
            case self::CONTEXT_DASHBOARD:
            case self::CONTEXT_MENU:
            default:
                return $this->isGranted($this->getPermissionsShow($context));
        }
    }

    
    public function createObjectSecurity($object)
    {
        $this->getSecurityHandler()->createObjectSecurity($this, $object);
    }

    
    public function setSecurityHandler(SecurityHandlerInterface $securityHandler)
    {
        $this->securityHandler = $securityHandler;
    }

    
    public function getSecurityHandler()
    {
        return $this->securityHandler;
    }

    
    public function isGranted($name, $object = null)
    {
        return $this->securityHandler->isGranted($this, $name, $object ?: $this);
    }

    
    public function getUrlsafeIdentifier($entity)
    {
        return $this->getModelManager()->getUrlsafeIdentifier($entity);
    }

    
    public function getNormalizedIdentifier($entity)
    {
        return $this->getModelManager()->getNormalizedIdentifier($entity);
    }

    
    public function id($entity)
    {
        return $this->getNormalizedIdentifier($entity);
    }

    
    public function setValidator(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    
    public function getValidator()
    {
        return $this->validator;
    }

    
    public function getShow()
    {
        $this->buildShow();

        return $this->show;
    }

    
    public function setFormTheme(array $formTheme)
    {
        $this->formTheme = $formTheme;
    }

    
    public function getFormTheme()
    {
        return $this->formTheme;
    }

    
    public function setFilterTheme(array $filterTheme)
    {
        $this->filterTheme = $filterTheme;
    }

    
    public function getFilterTheme()
    {
        return $this->filterTheme;
    }

    
    public function addExtension(AdminExtensionInterface $extension)
    {
        $this->extensions[] = $extension;
    }

    
    public function getExtensions()
    {
        return $this->extensions;
    }

    
    public function setMenuFactory(MenuFactoryInterface $menuFactory)
    {
        $this->menuFactory = $menuFactory;
    }

    
    public function getMenuFactory()
    {
        return $this->menuFactory;
    }

    
    public function setRouteBuilder(RouteBuilderInterface $routeBuilder)
    {
        $this->routeBuilder = $routeBuilder;
    }

    
    public function getRouteBuilder()
    {
        return $this->routeBuilder;
    }

    
    public function toString($object)
    {
        if (!is_object($object)) {
            return '';
        }

        if (method_exists($object, '__toString')) {
            return (string) $object;
        }

        return sprintf("%s:%s", get_class($object), spl_object_hash($object));
    }

    
    public function setLabelTranslatorStrategy(LabelTranslatorStrategyInterface $labelTranslatorStrategy)
    {
        $this->labelTranslatorStrategy = $labelTranslatorStrategy;
    }

    
    public function getLabelTranslatorStrategy()
    {
        return $this->labelTranslatorStrategy;
    }

    
    public function supportsPreviewMode()
    {
        return $this->supportsPreviewMode;
    }

    
    public function setPerPageOptions($options)
    {
        $this->perPageOptions = $options;
    }

    
    public function getPerPageOptions()
    {
        return $this->perPageOptions;
    }

    
    public function determinedPerPageValue($per_page)
    {
        return in_array($per_page, $this->perPageOptions);
    }

    
    protected function predefinePerPageOptions()
    {
        array_unshift($this->perPageOptions, $this->maxPerPage);
        $this->perPageOptions = array_unique($this->perPageOptions);
        sort($this->perPageOptions);
    }
}
}
 


namespace Sonata\AdminBundle\Admin
{

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;

use Knp\Menu\ItemInterface as MenuItemInterface;





interface AdminExtensionInterface
{
    
    public function configureFormFields(FormMapper $form);

    
    public function configureListFields(ListMapper $list);

    
    public function configureDatagridFilters(DatagridMapper $filter);

    
    public function configureShowFields(ShowMapper $filter);

    
    public function configureRoutes(AdminInterface $admin, RouteCollection $collection);

    
    public function configureSideMenu(AdminInterface $admin, MenuItemInterface $menu, $action, AdminInterface $childAdmin = null);

    
    public function validate(AdminInterface $admin, ErrorElement $errorElement, $object);

    
    public function configureQuery(AdminInterface $admin, ProxyQueryInterface $query, $context = 'list');
}
}
 


namespace Sonata\AdminBundle\Admin
{

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;

use Knp\Menu\ItemInterface as MenuItemInterface;

abstract class AdminExtension implements AdminExtensionInterface
{
    
    public function configureFormFields(FormMapper $form)
    {}

    
    public function configureListFields(ListMapper $list)
    {}

    
    public function configureDatagridFilters(DatagridMapper $filter)
    {}

    
    public function configureShowFields(ShowMapper $filter)
    {}

    
    public function configureRoutes(AdminInterface $admin, RouteCollection $collection)
    {}

    
    public function configureSideMenu(AdminInterface $admin, MenuItemInterface $menu, $action, AdminInterface $childAdmin = null)
    {}

    
    public function validate(AdminInterface $admin, ErrorElement $errorElement, $object)
    {}

    
    public function configureQuery(AdminInterface $admin, ProxyQueryInterface $query, $context = 'list')
    {}
}
}
 


namespace Sonata\AdminBundle\Admin
{

use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormView;
use Sonata\AdminBundle\Exception\NoValueException;
use Sonata\AdminBundle\Util\FormViewIterator;
use Sonata\AdminBundle\Util\FormBuilderIterator;

class AdminHelper
{
    protected $pool;

    
    public function __construct(Pool $pool)
    {
        $this->pool = $pool;
    }

    
    public function getChildFormBuilder(FormBuilder $formBuilder, $elementId)
    {
        foreach (new FormBuilderIterator($formBuilder) as $name => $formBuilder) {
            if ($name == $elementId) {
                return $formBuilder;
            }
        }

        return null;
    }

    
    public function getChildFormView(FormView $formView, $elementId)
    {
        foreach (new \RecursiveIteratorIterator(new FormViewIterator($formView), \RecursiveIteratorIterator::SELF_FIRST) as $name => $formView) {
            if ($name === $elementId) {
                return $formView;
            }
        }

        return null;
    }

    
    public function getAdmin($code)
    {
        return $this->pool->getInstance($code);
    }

    
    public function appendFormFieldElement(AdminInterface $admin, $subject, $elementId)
    {
                $formBuilder = $admin->getFormBuilder();

        $form = $formBuilder->getForm();
        $form->setData($subject);
        $form->bind($admin->getRequest());

                $childFormBuilder = $this->getChildFormBuilder($formBuilder, $elementId);

                $fieldDescription = $admin->getFormFieldDescription($childFormBuilder->getName());

        try {
            $value = $fieldDescription->getValue($form->getData());
        } catch (NoValueException $e) {
            $value = null;
        }

                $data = $admin->getRequest()->get($formBuilder->getName());

        if (!isset($data[$childFormBuilder->getName()])) {
            $data[$childFormBuilder->getName()] = array();
        }

        $objectCount = count($value);
        $postCount   = count($data[$childFormBuilder->getName()]);

        $fields = array_keys($fieldDescription->getAssociationAdmin()->getFormFieldDescriptions());

                $value = array();
        foreach ($fields as $name) {
            $value[$name] = '';
        }

                while ($objectCount < $postCount) {
                        $this->addNewInstance($form->getData(), $fieldDescription);
            $objectCount++;
        }

        $this->addNewInstance($form->getData(), $fieldDescription);
        $data[$childFormBuilder->getName()][] = $value;

        $finalForm = $admin->getFormBuilder()->getForm();
        $finalForm->setData($subject);

                $finalForm->setData($form->getData());

        return array($fieldDescription, $finalForm);
    }

    
    public function addNewInstance($object, FieldDescriptionInterface $fieldDescription)
    {
        $instance = $fieldDescription->getAssociationAdmin()->getNewInstance();
        $mapping  = $fieldDescription->getAssociationMapping();

        $method = sprintf('add%s', $this->camelize($mapping['fieldName']));

        if (!method_exists($object, $method)) {
            $method = rtrim($method, 's');

            if (!method_exists($object, $method)) {
                throw new \RuntimeException(sprintf('Please add a method %s in the %s class!', $method, get_class($object)));
            }
        }

        $object->$method($instance);
    }

    
    public function camelize($property)
    {
        return preg_replace(array('/(^|_| )+(.)/e', '/\.(.)/e'), array("strtoupper('\\2')", "'_'.strtoupper('\\1')"), $property);
    }
}
}
 



namespace Sonata\AdminBundle\Admin
{

use Sonata\AdminBundle\Admin\AdminInterface;

interface FieldDescriptionInterface
{

    
    public function setFieldName($fieldName);

    
    public function getFieldName();

    
    public function setName($name);

    
    public function getName();

    
    public function getOption($name, $default = null);

    
    public function setOption($name, $value);

    
    public function setOptions(array $options);

    
    public function getOptions();

    
    public function setTemplate($template);

    
    public function getTemplate();

    
    public function setType($type);

    
    public function getType();

    
    public function setParent(AdminInterface $parent);

    
    public function getParent();

    
    public function setAssociationMapping($associationMapping);

    
    public function getAssociationMapping();

    
    public function getTargetEntity();

    
    public function setFieldMapping($fieldMapping);

    
    public function getFieldMapping();

    
    public function setParentAssociationMappings(array $parentAssociationMappings);

    
    public function getParentAssociationMappings();

    
    public function setAssociationAdmin(AdminInterface $associationAdmin);

    
    public function getAssociationAdmin();

    
    public function isIdentifier();

    
    public function getValue($object);

    
    public function setAdmin(AdminInterface $admin);

    
    public function getAdmin();

    
    public function mergeOption($name, array $options = array());

    
    public function mergeOptions(array $options = array());

    
    public function setMappingType($mappingType);

    
    public function getMappingType();

    
    public function getLabel();

    
    public function isSortable();

    
    public function getSortFieldMapping();

    
    public function getSortParentAssociationMapping();

    
    public function getFieldValue($object, $fieldName);
}
}
 



namespace Sonata\AdminBundle\Admin
{

use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Exception\NoValueException;


abstract class BaseFieldDescription implements FieldDescriptionInterface
{
    
    protected $name;

    
    protected $type;

    
    protected $mappingType;

    
    protected $fieldName;

    
    protected $associationMapping;

    
    protected $fieldMapping;

    
    protected $parentAssociationMappings;

    
    protected $template;

    
    protected $options = array();

    
    protected $parent = null;

    
    protected $admin;

    
    protected $associationAdmin;

    
    protected $help;

    
    public function setFieldName($fieldName)
    {
        $this->fieldName = $fieldName;
    }

    
    public function getFieldName()
    {
        return $this->fieldName;
    }

    
    public function setName($name)
    {
        $this->name = $name;

        if (!$this->getFieldName()) {
            $this->setFieldName(substr(strrchr('.' . $name, '.'), 1));
        }
    }

    
    public function getName()
    {
        return $this->name;
    }

    
    public function getOption($name, $default = null)
    {
        return isset($this->options[$name]) ? $this->options[$name] : $default;
    }

    
    public function setOption($name, $value)
    {
        $this->options[$name] = $value;
    }

    
    public function setOptions(array $options)
    {
                if (isset($options['type'])) {
            $this->setType($options['type']);
            unset($options['type']);
        }

                if (isset($options['template'])) {
            $this->setTemplate($options['template']);
            unset($options['template']);
        }

                if (isset($options['help'])) {
            $this->setHelp($options['help']);
            unset($options['help']);
        }

        $this->options = $options;
    }

    
    public function getOptions()
    {
        return $this->options;
    }

    
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    
    public function getTemplate()
    {
        return $this->template;
    }

    
    public function setType($type)
    {
        $this->type = $type;
    }

    
    public function getType()
    {
        return $this->type;
    }

    
    public function setParent(AdminInterface $parent)
    {
        $this->parent = $parent;
    }

    
    public function getParent()
    {
        return $this->parent;
    }

    
    public function getAssociationMapping()
    {
        return $this->associationMapping;
    }

    
    public function getFieldMapping()
    {
        return $this->fieldMapping;
    }

    
    public function getParentAssociationMappings()
    {
        return $this->parentAssociationMappings;
    }

    
    public function setAssociationAdmin(AdminInterface $associationAdmin)
    {
        $this->associationAdmin = $associationAdmin;
        $this->associationAdmin->setParentFieldDescription($this);
    }

    
    public function getAssociationAdmin()
    {
        return $this->associationAdmin;
    }

    
    public function hasAssociationAdmin()
    {
        return $this->associationAdmin !== null;
    }

    
    public function getFieldValue($object, $fieldName)
    {
        $camelizedFieldName = self::camelize($fieldName);

        $getters = array();
                if ($this->getOption('code')) {
            $getters[] = $this->getOption('code');
        }
        $getters[] = 'get' . $camelizedFieldName;
        $getters[] = 'is' . $camelizedFieldName;

        foreach ($getters as $getter) {
            if (method_exists($object, $getter)) {
                return call_user_func(array($object, $getter));
            }
        }

        if (isset($object->{$fieldName})) {
            return $object->{$fieldName};
        }

        throw new NoValueException(sprintf('Unable to retrieve the value of `%s`', $this->getName()));
    }

    
    public function setAdmin(AdminInterface $admin)
    {
        $this->admin = $admin;
    }

    
    public function getAdmin()
    {
        return $this->admin;
    }

    
    public function mergeOption($name, array $options = array())
    {
        if (!isset($this->options[$name])) {
            $this->options[$name] = array();
        }

        if (!is_array($this->options[$name])) {
            throw new \RuntimeException(sprintf('The key `%s` does not point to an array value', $name));
        }

        $this->options[$name] = array_merge($this->options[$name], $options);
    }

    
    public function mergeOptions(array $options = array())
    {
        $this->setOptions(array_merge_recursive($this->options, $options));
    }

    
    public function setMappingType($mappingType)
    {
        $this->mappingType = $mappingType;
    }

    
    public function getMappingType()
    {
        return $this->mappingType;
    }

    
    public static function camelize($property)
    {
        return preg_replace(array('/(^|_| )+(.)/e', '/\.(.)/e'), array("strtoupper('\\2')", "'_'.strtoupper('\\1')"), $property);
    }

    
    public function setHelp($help)
    {
        $this->help = $help;
    }

    
    public function getHelp()
    {
        return $this->help;
    }

    
    public function getLabel()
    {
        return $this->getOption('label');
    }

    
    public function isSortable()
    {
        return $this->getOption('sortable', false);
    }

    
    public function getSortFieldMapping()
    {
        return $this->getOption('sort_field_mapping');
    }

    
    public function getSortParentAssociationMapping()
    {
        return $this->getOption('sort_parent_association_mappings');
    }
}
}
 

namespace Sonata\AdminBundle\Admin
{

use Sonata\AdminBundle\Admin\FieldDescriptionInterface;

class FieldDescriptionCollection implements \ArrayAccess, \Countable
{
    protected $elements = array();

    
    public function add(FieldDescriptionInterface $fieldDescription)
    {
        $this->elements[$fieldDescription->getName()] = $fieldDescription;
    }

    
    public function getElements()
    {
        return $this->elements;
    }

    
    public function has($name)
    {
        return array_key_exists($name, $this->elements);
    }

    
    public function get($name)
    {
        if ($this->has($name)) {
            return $this->elements[$name];
        }

        throw new \InvalidArgumentException(sprintf('Element "%s" does not exist.', $name));
    }

    
    public function remove($name)
    {
        if ($this->has($name)) {
            unset($this->elements[$name]);
        }
    }

    
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    
    public function offsetSet($offset, $value)
    {
        throw new \RunTimeException('Cannot set value, use add');
    }

    
    public function offsetUnset($offset)
    {
        $this->remove($offset);
    }

    
    public function count()
    {
        return count($this->elements);
    }

    
    public function reorder(array $keys)
    {
        if ($this->has('batch')) {
            array_unshift($keys, 'batch');
        }

        $this->elements = array_merge(array_flip($keys), $this->elements);
    }
}
}
 



namespace Sonata\AdminBundle\Admin
{

use Symfony\Component\DependencyInjection\ContainerInterface;

class Pool
{
    protected $container = null;

    protected $adminServiceIds = array();

    protected $adminGroups = array();

    protected $adminClasses = array();

    protected $templates    = array();

    protected $title;

    protected $titleLogo;

    
    public function __construct(ContainerInterface $container, $title, $logoTitle)
    {
        $this->container = $container;
        $this->title     = $title;
        $this->titleLogo = $logoTitle;
    }

    
    public function getGroups()
    {
        $groups = $this->adminGroups;

        foreach ($this->adminGroups as $name => $adminGroup) {
            foreach ($adminGroup as $id => $options) {
                $groups[$name][$id] = $this->getInstance($id);
            }
        }

        return $groups;
    }

    
    public function getDashboardGroups()
    {
        $groups = $this->adminGroups;

        foreach ($this->adminGroups as $name => $adminGroup) {
            if (isset($adminGroup['items'])) {
                foreach ($adminGroup['items'] as $key => $id) {
                    $admin = $this->getInstance($id);

                    if ($admin->showIn(Admin::CONTEXT_DASHBOARD)) {
                        $groups[$name]['items'][$key] = $admin;
                    } else {
                        unset($groups[$name]['items'][$key]);
                    }
                }
            }

            if (empty($groups[$name]['items'])) {
                unset($groups[$name]);
            }
        }

        return $groups;
    }

    
    public function getAdminByClass($class)
    {
        if (!$this->hasAdminByClass($class)) {
            return null;
        }

        return $this->getInstance($this->adminClasses[$class]);
    }

    
    public function hasAdminByClass($class)
    {
        return isset($this->adminClasses[$class]);
    }

    
    public function getAdminByAdminCode($adminCode)
    {
        $codes = explode('|', $adminCode);
        $admin = false;
        foreach ($codes as $code) {
            if ($admin == false) {
                $admin = $this->getInstance($code);
            } elseif ($admin->hasChild($code)) {
                $admin = $admin->getChild($code);
            }
        }

        return $admin;
    }

    
    public function getInstance($id)
    {
        return $this->container->get($id);
    }

    
    public function getContainer()
    {
        return $this->container;
    }

    
    public function setAdminGroups(array $adminGroups)
    {
        $this->adminGroups = $adminGroups;
    }

    
    public function getAdminGroups()
    {
        return $this->adminGroups;
    }

    
    public function setAdminServiceIds(array $adminServiceIds)
    {
        $this->adminServiceIds = $adminServiceIds;
    }

    
    public function getAdminServiceIds()
    {
        return $this->adminServiceIds;
    }

    
    public function setAdminClasses(array $adminClasses)
    {
        $this->adminClasses = $adminClasses;
    }

    
    public function getAdminClasses()
    {
        return $this->adminClasses;
    }

    
    public function setTemplates(array $templates)
    {
        $this->templates = $templates;
    }

    
    public function getTemplates()
    {
        return $this->templates;
    }

    
    public function getTemplate($name)
    {
        if (isset($this->templates[$name])) {
            return $this->templates[$name];
        }

        return null;
    }

    
    public function getTitleLogo()
    {
        return $this->titleLogo;
    }

    
    public function getTitle()
    {
        return $this->title;
    }
}
}
 



namespace Sonata\BlockBundle\Block
{

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\AdminBundle\Validator\ErrorElement;

use Symfony\Component\HttpFoundation\Response;

interface BlockServiceInterface
{
    
    public function buildEditForm(FormMapper $form, BlockInterface $block);

    
    public function buildCreateForm(FormMapper $form, BlockInterface $block);

    
    public function execute(BlockInterface $block, Response $response = null);

    
    public function validateBlock(ErrorElement $errorElement, BlockInterface $block);

    
    public function getName();

    
    public function getDefaultSettings();

    
    public function load(BlockInterface $block);

    
    public function getJavascripts($media);

    
    public function getStylesheets($media);

    
    public function getCacheKeys(BlockInterface $block);
}
}
 



namespace Sonata\BlockBundle\Block
{

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\AdminBundle\Form\FormMapper;


abstract class BaseBlockService implements BlockServiceInterface
{
    protected $name;

    protected $templating;

    
    public function __construct($name, EngineInterface $templating)
    {
        $this->name       = $name;
        $this->templating = $templating;
    }

    
    public function renderResponse($view, array $parameters = array(), Response $response = null)
    {
        return $this->getTemplating()->renderResponse($view, $parameters, $response);
    }

    
    public function getName()
    {
        return $this->name;
    }

     
    public function getTemplating()
    {
        return $this->templating;
    }

    
    public function buildCreateForm(FormMapper $formMapper, BlockInterface $block)
    {
        $this->buildEditForm($formMapper, $block);
    }

    
    public function getCacheKeys(BlockInterface $block)
    {
        return array(
            'block_id'   => $block->getId(),
            'updated_at' => $block->getUpdatedAt()->format('U'),
        );
    }

    
    public function prePersist(BlockInterface $block)
    {
    }

    
    public function postPersist(BlockInterface $block)
    {
    }

    
    public function preUpdate(BlockInterface $block)
    {
    }

    
    public function postUpdate(BlockInterface $block)
    {
    }

    
    public function preDelete(BlockInterface $block)
    {
    }

    
    public function postDelete(BlockInterface $block)
    {
    }

    
    public function load(BlockInterface $block)
    {
    }

    
    public function getJavascripts($media)
    {
        return array();
    }

    
    public function getStylesheets($media)
    {
        return array();
    }

    
    public function getDefaultSettings()
    {
        return array();
    }
}
}
 



namespace Sonata\AdminBundle\Block
{

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Admin\Pool;

use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\BlockBundle\Block\BaseBlockService;


class AdminListBlockService extends BaseBlockService
{
    protected $pool;

    
    public function __construct($name, EngineInterface $templating, Pool $pool)
    {
        parent::__construct($name, $templating);

        $this->pool = $pool;
    }

    
    public function execute(BlockInterface $block, Response $response = null)
    {
        $settings = array_merge($this->getDefaultSettings(), $block->getSettings());

        $dashboardGroups = $this->pool->getDashboardGroups();

        $visibleGroups = array();
        foreach ($dashboardGroups as $name => $dashboardGroup) {
            if (!$settings['groups'] || in_array($name, $settings['groups'])) {
                $visibleGroups[] = $dashboardGroup;
            }
        }

        return $this->renderResponse($this->pool->getTemplate('list_block'), array(
            'block'         => $block,
            'settings'      => $settings,
            'admin_pool'    => $this->pool,
            'groups'        => $visibleGroups
        ), $response);
    }

    
    public function validateBlock(ErrorElement $errorElement, BlockInterface $block)
    {
            }

    
    public function buildEditForm(FormMapper $formMapper, BlockInterface $block)
    {

    }

    
    public function getName()
    {
        return 'Admin List';
    }

    
    public function getDefaultSettings()
    {
        return array(
            'groups' => false
        );
    }
}
}
 



namespace Sonata\AdminBundle\Builder
{

use Sonata\AdminBundle\Admin\FieldDescriptionInterface;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\DatagridInterface;

interface DatagridBuilderInterface
{
    
    public function fixFieldDescription(AdminInterface $admin, FieldDescriptionInterface $fieldDescription);

    
    public function addFilter(DatagridInterface $datagrid, $type = null, FieldDescriptionInterface $fieldDescription, AdminInterface $admin);

    
    public function getBaseDatagrid(AdminInterface $admin, array $values = array());
}
}
 



namespace Sonata\AdminBundle\Builder
{

use Sonata\AdminBundle\Admin\FieldDescriptionInterface;
use Sonata\AdminBundle\Admin\AdminInterface;

use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormFactoryInterface;

interface FormContractorInterface
{

    
    public function __construct(FormFactoryInterface $formFactory);

    
    public function fixFieldDescription(AdminInterface $admin, FieldDescriptionInterface $fieldDescription);

    
    public function getFormBuilder($name, array $options = array());

    
    public function getDefaultOptions($type, FieldDescriptionInterface $fieldDescription);
}
}
 



namespace Sonata\AdminBundle\Builder
{

use Sonata\AdminBundle\Admin\FieldDescriptionInterface;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Admin\FieldDescriptionCollection;

interface ListBuilderInterface
{
    
    public function getBaseList(array $options = array());

    
    public function buildField($type = null, FieldDescriptionInterface $fieldDescription, AdminInterface $admin);

    
    public function addField(FieldDescriptionCollection $list, $type = null, FieldDescriptionInterface $fieldDescription, AdminInterface $admin);

    
    public function fixFieldDescription(AdminInterface $admin, FieldDescriptionInterface $fieldDescription);
}
}
 



namespace Sonata\AdminBundle\Builder
{

use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Route\RouteCollection;

interface RouteBuilderInterface
{

    
    public function build(AdminInterface $admin, RouteCollection $collection);
}
}
 



namespace Sonata\AdminBundle\Builder
{

use Sonata\AdminBundle\Admin\FieldDescriptionInterface;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Admin\FieldDescriptionCollection;

interface ShowBuilderInterface
{
    
    public function getBaseList(array $options = array());

    
    public function addField(FieldDescriptionCollection $list, $type = null, FieldDescriptionInterface $fieldDescription, AdminInterface $admin);

    
    public function fixFieldDescription(AdminInterface $admin, FieldDescriptionInterface $fieldDescription);
}
}
 

namespace Sonata\AdminBundle\Datagrid
{

use Sonata\AdminBundle\Filter\FilterInterface;

interface DatagridInterface
{
    
    public function getPager();

    
    public function getQuery();

    
    public function getResults();

    
    public function buildPager();

    
    public function addFilter(FilterInterface $filter);

    
    public function getFilters();

    
    public function getValues();

    
    public function getColumns();

    
    public function setValue($name, $operator, $value);

    
    public function getForm();

    
    public function getFilter($name);

    
    public function hasFilter($name);

    
    public function removeFilter($name);

    
    public function hasActiveFilters();
}
}
 



namespace Sonata\AdminBundle\Datagrid
{

use Sonata\AdminBundle\Datagrid\PagerInterface;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\AdminBundle\Filter\FilterInterface;
use Sonata\AdminBundle\Admin\FieldDescriptionCollection;
use Sonata\AdminBundle\Admin\FieldDescriptionInterface;

use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\CallbackTransformer;

class Datagrid implements DatagridInterface
{
    
    protected $filters = array();

    protected $values;

    protected $columns;

    protected $pager;

    protected $bound = false;

    protected $query;

    protected $formBuilder;

    protected $form;

    protected $results;

    
    public function __construct(ProxyQueryInterface $query, FieldDescriptionCollection $columns, PagerInterface $pager, FormBuilder $formBuilder, array $values = array())
    {
        $this->pager       = $pager;
        $this->query       = $query;
        $this->values      = $values;
        $this->columns     = $columns;
        $this->formBuilder = $formBuilder;
    }

    
    public function getPager()
    {
        return $this->pager;
    }

    
    public function getResults()
    {
        $this->buildPager();

        if (!$this->results) {
            $this->results = $this->pager->getResults();
        }

        return $this->results;
    }

    
    public function buildPager()
    {
        if ($this->bound) {
            return;
        }

        foreach ($this->getFilters() as $name => $filter) {
            list($type, $options) = $filter->getRenderSettings();

            $this->formBuilder->add($filter->getFormName(), $type, $options);
        }

        $this->formBuilder->add('_sort_by', 'hidden');
        $this->formBuilder->get('_sort_by')->addViewTransformer(new CallbackTransformer(
            function($value) { return $value; },
            function($value) { return $value instanceof FieldDescriptionInterface ? $value->getName() : $value; }
        ));

        $this->formBuilder->add('_sort_order', 'hidden');
        $this->formBuilder->add('_page', 'hidden');
        $this->formBuilder->add('_per_page', 'hidden');

        $this->form = $this->formBuilder->getForm();
        $this->form->bind($this->values);

        $data = $this->form->getData();

        foreach ($this->getFilters() as $name => $filter) {
            $this->values[$name] = isset($this->values[$name]) ? $this->values[$name] : null;
            $filter->apply($this->query, $data[$filter->getFormName()]);
        }

        if (isset($this->values['_sort_by'])) {
            if (!$this->values['_sort_by'] instanceof FieldDescriptionInterface) {
                throw new UnexpectedTypeException($this->values['_sort_by'],'FieldDescriptionInterface');
            }

            if ($this->values['_sort_by']->isSortable()) {
                $this->query->setSortBy($this->values['_sort_by']->getSortParentAssociationMapping(), $this->values['_sort_by']->getSortFieldMapping());
                $this->query->setSortOrder(isset($this->values['_sort_order']) ? $this->values['_sort_order'] : null);
            }
        }

        $this->pager->setMaxPerPage(isset($this->values['_per_page']) ? $this->values['_per_page'] : 25);
        $this->pager->setPage(isset($this->values['_page']) ? $this->values['_page'] : 1);
        $this->pager->setQuery($this->query);
        $this->pager->init();

        $this->bound = true;
    }

    
    public function addFilter(FilterInterface $filter)
    {
        $this->filters[$filter->getName()] = $filter;
    }

    
    public function hasFilter($name)
    {
        return isset($this->filters[$name]);
    }

    
    public function removeFilter($name)
    {
        unset($this->filters[$name]);
    }

    
    public function getFilter($name)
    {
        return $this->hasFilter($name) ? $this->filters[$name] : null;
    }

    
    public function getFilters()
    {
        return $this->filters;
    }

    public function reorderFilters(array $keys)
    {
        $this->filters = array_merge(array_flip($keys), $this->filters);
    }

    
    public function getValues()
    {
        return $this->values;
    }

    
    public function setValue($name, $operator, $value)
    {
        $this->values[$name] = array(
            'type'  => $operator,
            'value' => $value
        );
    }

    
    public function hasActiveFilters()
    {
        foreach ($this->filters as $name => $filter) {
            if ($filter->isActive()) {
                return true;
            }
        }

        return false;
    }

    
    public function getColumns()
    {
        return $this->columns;
    }

    
    public function getQuery()
    {
        return $this->query;
    }

    
    public function getForm()
    {
        $this->buildPager();

        return $this->form;
    }
}
}
 

namespace Sonata\AdminBundle\Datagrid
{

use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Admin\FieldDescriptionInterface;
use Sonata\AdminBundle\Datagrid\DatagridInterface;
use Sonata\AdminBundle\Builder\DatagridBuilderInterface;


class DatagridMapper
{
    protected $datagridBuilder;

    protected $datagrid;

    protected $admin;

    
    public function __construct(DatagridBuilderInterface $datagridBuilder, DatagridInterface $datagrid, AdminInterface $admin)
    {
        $this->datagridBuilder = $datagridBuilder;
        $this->datagrid        = $datagrid;
        $this->admin           = $admin;
    }

    
    public function add($name, $type = null, array $filterOptions = array(), $fieldType = null, $fieldOptions = null)
    {
        if (is_array($fieldOptions)) {
            $filterOptions['field_options'] = $fieldOptions;
        }

        if ($fieldType) {
            $filterOptions['field_type'] = $fieldType;
        }

        $filterOptions['field_name'] = isset($filterOptions['field_name']) ? $filterOptions['field_name'] : substr(strrchr('.'.$name,'.'), 1);

        if ($name instanceof FieldDescriptionInterface) {
            $fieldDescription = $name;
            $fieldDescription->mergeOptions($filterOptions);
        } elseif (is_string($name) && !$this->admin->hasFilterFieldDescription($name)) {
            $fieldDescription = $this->admin->getModelManager()->getNewFieldDescriptionInstance(
                $this->admin->getClass(),
                $name,
                $filterOptions
            );
        } else {
            throw new \RuntimeException('invalid state');
        }

                $this->datagridBuilder->addFilter($this->datagrid, $type, $fieldDescription, $this->admin);

        return $this;
    }

    
    public function get($name)
    {
        return $this->datagrid->getFilter($name);
    }

    
    public function has($key)
    {
        return $this->datagrid->hasFilter($key);
    }

    
    public function remove($key)
    {
        $this->admin->removeFilterFieldDescription($key);
        $this->datagrid->removeFilter($key);

        return $this;
    }

    
    public function reorder(array $keys)
    {
        $this->datagrid->reorderFilters($keys);

        return $this;
    }
}
}
 

namespace Sonata\AdminBundle\Datagrid
{

use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Admin\FieldDescriptionInterface;
use Sonata\AdminBundle\Admin\FieldDescriptionCollection;
use Sonata\AdminBundle\Builder\ListBuilderInterface;


class ListMapper
{
    protected $listBuilder;

    protected $list;

    protected $admin;

    
    public function __construct(ListBuilderInterface $listBuilder, FieldDescriptionCollection $list, AdminInterface $admin)
    {
        $this->listBuilder = $listBuilder;
        $this->list        = $list;
        $this->admin       = $admin;
    }

    
    public function addIdentifier($name, $type = null, array $fieldDescriptionOptions = array())
    {
        $fieldDescriptionOptions['identifier'] = true;

        if (!isset($fieldDescriptionOptions['route']['name'])) {
            $fieldDescriptionOptions['route']['name'] = 'edit';
        }

        if (!isset($fieldDescriptionOptions['route']['parameters'])) {
            $fieldDescriptionOptions['route']['parameters'] = array();
        }

        return $this->add($name, $type, $fieldDescriptionOptions);
    }

    
    public function add($name, $type = null, array $fieldDescriptionOptions = array())
    {
        if ($name instanceof FieldDescriptionInterface) {
            $fieldDescription = $name;
            $fieldDescription->mergeOptions($fieldDescriptionOptions);
        } elseif (is_string($name) && !$this->admin->hasListFieldDescription($name)) {
            $fieldDescription = $this->admin->getModelManager()->getNewFieldDescriptionInstance(
                $this->admin->getClass(),
                $name,
                $fieldDescriptionOptions
            );
        } else {
            throw new \RuntimeException('Unknown or duplicate field name in list mapper. Field name should be either of FieldDescriptionInterface interface or string. Names should be unique.');
        }

        if (!$fieldDescription->getLabel()) {
            $fieldDescription->setOption('label', $this->admin->getLabelTranslatorStrategy()->getLabel($fieldDescription->getName(), 'list', 'label'));
        }

                $this->listBuilder->addField($this->list, $type, $fieldDescription, $this->admin);

        return $this;
    }

    
    public function get($name)
    {
        return $this->list->get($name);
    }

    
    public function has($key)
    {
        return $this->list->has($key);
    }

    
    public function remove($key)
    {
        $this->admin->removeListFieldDescription($key);
        $this->list->remove($key);

        return $this;
    }

    
    public function reorder(array $keys)
    {
        $this->list->reorder($keys);

        return $this;
    }
}
}
 

namespace Sonata\AdminBundle\Datagrid
{

interface PagerInterface
{
    
    public function init();
}
}
 



namespace Sonata\AdminBundle\Datagrid
{


abstract class Pager implements \Iterator, \Countable, \Serializable, PagerInterface
{

    protected $page = 1;
    protected $maxPerPage = 0;
    protected $lastPage = 1;
    protected $nbResults = 0;
    protected $cursor = 1;
    protected $parameters = array();
    protected $currentMaxLink = 1;
    protected $maxRecordLimit = false;
    protected $maxPageLinks = 0;

        protected $results = null;
    protected $resultsCounter = 0;
    protected $query = null;
    protected $countColumn = array('id');

    
    public function __construct($maxPerPage = 10)
    {
        $this->setMaxPerPage($maxPerPage);
    }

    
    abstract public function getResults();

    
    public function getCurrentMaxLink()
    {
        return $this->currentMaxLink;
    }

    
    public function getMaxRecordLimit()
    {
        return $this->maxRecordLimit;
    }

    
    public function setMaxRecordLimit($limit)
    {
        $this->maxRecordLimit = $limit;
    }

    
    public function getLinks($nb_links = null)
    {
        if ($nb_links == null) {
            $nb_links = $this->getMaxPageLinks();
        }
        $links = array();
        $tmp   = $this->page - floor($nb_links / 2);
        $check = $this->lastPage - $nb_links + 1;
        $limit = $check > 0 ? $check : 1;
        $begin = $tmp > 0 ? ($tmp > $limit ? $limit : $tmp) : 1;

        $i = (int) $begin;
        while ($i < $begin + $nb_links && $i <= $this->lastPage) {
            $links[] = $i++;
        }

        $this->currentMaxLink = count($links) ? $links[count($links) - 1] : 1;

        return $links;
    }

    
    public function haveToPaginate()
    {
        return $this->getMaxPerPage() && $this->getNbResults() > $this->getMaxPerPage();
    }

    
    public function getCursor()
    {
        return $this->cursor;
    }

    
    public function setCursor($pos)
    {
        if ($pos < 1) {
            $this->cursor = 1;
        } else {
            if ($pos > $this->nbResults) {
                $this->cursor = $this->nbResults;
            } else {
                $this->cursor = $pos;
            }
        }
    }

    
    public function getObjectByCursor($pos)
    {
        $this->setCursor($pos);

        return $this->getCurrent();
    }

    
    public function getCurrent()
    {
        return $this->retrieveObject($this->cursor);
    }

    
    public function getNext()
    {
        if ($this->cursor + 1 > $this->nbResults) {
            return null;
        } else {
            return $this->retrieveObject($this->cursor + 1);
        }
    }

    
    public function getPrevious()
    {
        if ($this->cursor - 1 < 1) {
            return null;
        } else {
            return $this->retrieveObject($this->cursor - 1);
        }
    }

    
    public function getFirstIndice()
    {
        if ($this->page == 0) {
            return 1;
        } else {
            return ($this->page - 1) * $this->maxPerPage + 1;
        }
    }

    
    public function getLastIndice()
    {
        if ($this->page == 0) {
            return $this->nbResults;
        } else {
            if ($this->page * $this->maxPerPage >= $this->nbResults) {
                return $this->nbResults;
            } else {
                return $this->page * $this->maxPerPage;
            }
        }
    }

    
    public function getNbResults()
    {
        return $this->nbResults;
    }

    
    protected function setNbResults($nb)
    {
        $this->nbResults = $nb;
    }

    
    public function getFirstPage()
    {
        return 1;
    }

    
    public function getLastPage()
    {
        return $this->lastPage;
    }

    
    protected function setLastPage($page)
    {
        $this->lastPage = $page;

        if ($this->getPage() > $page) {
            $this->setPage($page);
        }
    }

    
    public function getPage()
    {
        return $this->page;
    }

    
    public function getNextPage()
    {
        return min($this->getPage() + 1, $this->getLastPage());
    }

    
    public function getPreviousPage()
    {
        return max($this->getPage() - 1, $this->getFirstPage());
    }

    
    public function setPage($page)
    {
        $this->page = intval($page);

        if ($this->page <= 0) {
                        $this->page = $this->getMaxPerPage() ? 1 : 0;
        }
    }

    
    public function getMaxPerPage()
    {
        return $this->maxPerPage;
    }

    
    public function setMaxPerPage($max)
    {
        if ($max > 0) {
            $this->maxPerPage = $max;
            if ($this->page == 0) {
                $this->page = 1;
            }
        } else {
            if ($max == 0) {
                $this->maxPerPage = 0;
                $this->page       = 0;
            } else {
                $this->maxPerPage = 1;
                if ($this->page == 0) {
                    $this->page = 1;
                }
            }
        }
    }

    
    public function getMaxPageLinks()
    {
        return $this->maxPageLinks;
    }

    
    public function setMaxPageLinks($maxPageLinks)
    {
        $this->maxPageLinks = $maxPageLinks;
    }

    
    public function isFirstPage()
    {
        return 1 == $this->page;
    }

    
    public function isLastPage()
    {
        return $this->page == $this->lastPage;
    }

    
    public function getParameters()
    {
        return $this->parameters;
    }

    
    public function getParameter($name, $default = null)
    {
        return isset($this->parameters[$name]) ? $this->parameters[$name] : $default;
    }

    
    public function hasParameter($name)
    {
        return isset($this->parameters[$name]);
    }

    
    public function setParameter($name, $value)
    {
        $this->parameters[$name] = $value;
    }

    
    protected function isIteratorInitialized()
    {
        return null !== $this->results;
    }

    
    protected function initializeIterator()
    {
        $this->results        = $this->getResults();
        $this->resultsCounter = count($this->results);
    }

    
    protected function resetIterator()
    {
        $this->results        = null;
        $this->resultsCounter = 0;
    }

    
    public function current()
    {
        if (!$this->isIteratorInitialized()) {
            $this->initializeIterator();
        }

        return current($this->results);
    }

    
    public function key()
    {
        if (!$this->isIteratorInitialized()) {
            $this->initializeIterator();
        }

        return key($this->results);
    }

    
    public function next()
    {
        if (!$this->isIteratorInitialized()) {
            $this->initializeIterator();
        }

        --$this->resultsCounter;

        return next($this->results);
    }

    
    public function rewind()
    {
        if (!$this->isIteratorInitialized()) {
            $this->initializeIterator();
        }

        $this->resultsCounter = count($this->results);

        return reset($this->results);
    }

    
    public function valid()
    {
        if (!$this->isIteratorInitialized()) {
            $this->initializeIterator();
        }

        return $this->resultsCounter > 0;
    }

    
    public function count()
    {
        return $this->getNbResults();
    }

    
    public function serialize()
    {
        $vars = get_object_vars($this);
        unset($vars['query']);

        return serialize($vars);
    }

    
    public function unserialize($serialized)
    {
        $array = unserialize($serialized);

        foreach ($array as $name => $values) {
            $this->$name = $values;
        }
    }

    
    public function getCountColumn()
    {
        return $this->countColumn;
    }

    
    public function setCountColumn(array $countColumn)
    {
        return $this->countColumn = $countColumn;
    }

    
    protected function retrieveObject($offset)
    {
        $queryForRetrieve = clone $this->getQuery();
        $queryForRetrieve
            ->setFirstResult($offset - 1)
            ->setMaxResults(1);

        $results = $queryForRetrieve->execute();

        return $results[0];
    }

    
    public function setQuery($query)
    {
        $this->query = $query;
    }

    
    public function getQuery()
    {
        return $this->query;
    }
}
}
 


namespace Sonata\AdminBundle\Datagrid
{


interface ProxyQueryInterface
{
    
    public function execute(array $params = array(), $hydrationMode = null);

    
    public function __call($name, $args);

    
    public function setSortBy($parentAssociationMappings, $fieldMapping);

    
    public function getSortBy();

    
    public function setSortOrder($sortOrder);

    
    public function getSortOrder();

    
    public function getSingleScalarResult();

    
    public function setFirstResult($firstResult);

    
    public function getFirstResult();

    
    public function setMaxResults($maxResults);

    
    public function getMaxResults();

    
    public function getUniqueParameterId();

    
    public function entityJoin(array $associationMappings);
}
}
 



namespace Sonata\AdminBundle\Exception
{

class ModelManagerException extends \Exception
{

}
}
 



namespace Sonata\AdminBundle\Exception
{

class NoValueException extends \Exception
{

}
}
 



namespace Sonata\AdminBundle\Export
{

use Exporter\Source\SourceIteratorInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;

use Exporter\Writer\XlsWriter;
use Exporter\Writer\XmlWriter;
use Exporter\Writer\JsonWriter;
use Exporter\Writer\CsvWriter;

class Exporter
{
    
    public function getResponse($format, $filename, SourceIteratorInterface $source)
    {
        switch ($format) {
            case 'xls':
                $writer      = new XlsWriter('php://output');
                $contentType = 'application/vnd.ms-excel';
                break;
            case 'xml':
                $writer      = new XmlWriter('php://output');
                $contentType = 'text/xml';
                break;
            case 'json':
                $writer      = new JsonWriter('php://output');
                $contentType = 'application/json';
                break;
            case 'csv':
                $writer      = new CsvWriter('php://output', ',', '"', "", true, true);
                $contentType = 'text/csv';
                break;
            default:
                throw new \RuntimeException('Invalid format');
        }

        $callback = function() use ($source, $writer) {
            $handler = \Exporter\Handler::create($source, $writer);
            $handler->export();
        };

        return new StreamedResponse($callback, 200, array(
            'Content-Type'        => $contentType,
            'Content-Disposition' => sprintf('attachment; filename=%s', $filename)
        ));
    }
}
}
 



namespace Sonata\AdminBundle\Filter
{
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;

interface FilterInterface
{
    
    public function filter(ProxyQueryInterface $queryBuilder, $alias, $field, $value);

    
    public function apply($query, $value);

    
    public function getName();

    
    public function getFormName();

    
    public function getLabel();

    
    public function setLabel($label);

    
    public function getDefaultOptions();

    
    public function getOption($name, $default = null);

    
    public function setOption($name, $value);

    
    public function initialize($name, array $options = array());

    
    public function getFieldName();

    
    public function getParentAssociationMappings();

    
    public function getFieldMapping();

    
    public function getAssociationMapping();

    
    public function getFieldOptions();

    
    public function getFieldType();

    
    public function getRenderSettings();
}
}
 



namespace Sonata\AdminBundle\Filter
{

use Sonata\AdminBundle\Filter\FilterInterface;

abstract class Filter implements FilterInterface
{
    protected $name = null;

    protected $value = null;

    protected $options = array();

    protected $condition;

    const CONDITION_OR = 'OR';

    const CONDITION_AND = 'AND';

    
    public function initialize($name, array $options = array())
    {
        $this->name = $name;
        $this->setOptions($options);
    }

    
    public function getName()
    {
        return $this->name;
    }

    
    public function getFormName()
    {
        

        return str_replace('.', '__', $this->name);
    }

    
    public function getOption($name, $default = null)
    {
        if (array_key_exists($name, $this->options)) {
            return $this->options[$name];
        }

        return $default;
    }

    
    public function setOption($name, $value)
    {
        $this->options[$name] = $value;
    }

    
    public function getFieldType()
    {
        return $this->getOption('field_type', 'text');
    }

    
    public function getFieldOptions()
    {
        return $this->getOption('field_options', array('required' => false));
    }

    
    public function getLabel()
    {
        return $this->getOption('label');
    }

    
    public function setLabel($label)
    {
        $this->setOption('label', $label);
    }

    
    public function getFieldName()
    {
        $fieldName = $this->getOption('field_name');

        if (!$fieldName) {
            throw new \RunTimeException(sprintf('The option `field_name` must be set for field : `%s`', $this->getName()));
        }

        return $fieldName;
    }

    
    public function getParentAssociationMappings()
    {
        return $this->getOption('parent_association_mappings', array());
    }

    
    public function getFieldMapping()
    {
        $fieldMapping = $this->getOption('field_mapping');

        if (!$fieldMapping) {
            throw new \RunTimeException(sprintf('The option `field_mapping` must be set for field : `%s`', $this->getName()));
        }

        return $fieldMapping;
    }

    
    public function getAssociationMapping()
    {
        $associationMapping = $this->getOption('association_mapping');

        if (!$associationMapping) {
            throw new \RunTimeException(sprintf('The option `association_mapping` must be set for field : `%s`', $this->getName()));
        }

        return $associationMapping;
    }

    
    public function setOptions(array $options)
    {
        $this->options = array_merge($this->getDefaultOptions(), $options);
    }

    
    public function getOptions()
    {
        return $this->options;
    }

    
    public function setValue($value)
    {
        $this->value = $value;
    }

    
    public function getValue()
    {
        return $this->value;
    }

    
    public function isActive()
    {
        $values = $this->getValue();

        return !empty($values['value']);
    }

    
    public function setCondition($condition)
    {
        $this->condition = $condition;
    }

    
    public function getCondition()
    {
        return $this->condition;
    }
}
}
 



namespace Sonata\AdminBundle\Filter
{

interface FilterFactoryInterface
{
    
    public function create($name, $type, array $options = array());
}
}
 



namespace Sonata\AdminBundle\Filter
{

use Symfony\Component\DependencyInjection\ContainerInterface;

class FilterFactory implements FilterFactoryInterface
{
    protected $container;

    protected $types;

    
    public function __construct(ContainerInterface $container, array $types = array())
    {
        $this->container = $container;
        $this->types     = $types;
    }

    
    public function create($name, $type, array $options = array())
    {
        if (!$type) {
            throw new \RunTimeException('The type must be defined');
        }

        $id = isset($this->types[$type]) ? $this->types[$type] : false;

        if (!$id) {
            throw new \RunTimeException(sprintf('No attached service to type named `%s`', $type));
        }

        $filter = $this->container->get($id);

        if (!$filter instanceof FilterInterface) {
            throw new \RunTimeException(sprintf('The service `%s` must implement `FilterInterface`', $id));
        }

        $filter->initialize($name, $options);

        return $filter;
    }
}
}
 



namespace Symfony\Component\Form\Extension\Core\ChoiceList
{


interface ChoiceListInterface
{
    
    public function getChoices();

    
    public function getValues();

    
    public function getPreferredViews();

    
    public function getRemainingViews();

    
    public function getChoicesForValues(array $values);

    
    public function getValuesForChoices(array $choices);

    
    public function getIndicesForChoices(array $choices);

    
    public function getIndicesForValues(array $values);
}
}
 



namespace Symfony\Component\Form\Extension\Core\ChoiceList
{

use Symfony\Component\Form\FormConfigBuilder;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Exception\InvalidConfigurationException;
use Symfony\Component\Form\Extension\Core\View\ChoiceView;


class ChoiceList implements ChoiceListInterface
{
    
    private $choices = array();

    
    private $values = array();

    
    private $preferredViews = array();

    
    private $remainingViews = array();

    
    public function __construct($choices, array $labels, array $preferredChoices = array())
    {
        if (!is_array($choices) && !$choices instanceof \Traversable) {
            throw new UnexpectedTypeException($choices, 'array or \Traversable');
        }

        $this->initialize($choices, $labels, $preferredChoices);
    }

    
    protected function initialize($choices, array $labels, array $preferredChoices)
    {
        $this->choices = array();
        $this->values = array();
        $this->preferredViews = array();
        $this->remainingViews = array();

        $this->addChoices(
            $this->preferredViews,
            $this->remainingViews,
            $choices,
            $labels,
            $preferredChoices
        );
    }

    
    public function getChoices()
    {
        return $this->choices;
    }

    
    public function getValues()
    {
        return $this->values;
    }

    
    public function getPreferredViews()
    {
        return $this->preferredViews;
    }

    
    public function getRemainingViews()
    {
        return $this->remainingViews;
    }

    
    public function getChoicesForValues(array $values)
    {
        $values = $this->fixValues($values);
        $choices = array();

        foreach ($values as $j => $givenValue) {
            foreach ($this->values as $i => $value) {
                if ($value === $givenValue) {
                    $choices[] = $this->choices[$i];
                    unset($values[$j]);

                    if (0 === count($values)) {
                        break 2;
                    }
                }
            }
        }

        return $choices;
    }

    
    public function getValuesForChoices(array $choices)
    {
        $choices = $this->fixChoices($choices);
        $values = array();

        foreach ($this->choices as $i => $choice) {
            foreach ($choices as $j => $givenChoice) {
                if ($choice === $givenChoice) {
                    $values[] = $this->values[$i];
                    unset($choices[$j]);

                    if (0 === count($choices)) {
                        break 2;
                    }
                }
            }
        }

        return $values;
    }

    
    public function getIndicesForChoices(array $choices)
    {
        $choices = $this->fixChoices($choices);
        $indices = array();

        foreach ($this->choices as $i => $choice) {
            foreach ($choices as $j => $givenChoice) {
                if ($choice === $givenChoice) {
                    $indices[] = $i;
                    unset($choices[$j]);

                    if (0 === count($choices)) {
                        break 2;
                    }
                }
            }
        }

        return $indices;
    }

    
    public function getIndicesForValues(array $values)
    {
        $values = $this->fixValues($values);
        $indices = array();

        foreach ($this->values as $i => $value) {
            foreach ($values as $j => $givenValue) {
                if ($value === $givenValue) {
                    $indices[] = $i;
                    unset($values[$j]);

                    if (0 === count($values)) {
                        break 2;
                    }
                }
            }
        }

        return $indices;
    }

    
    protected function addChoices(array &$bucketForPreferred, array &$bucketForRemaining, $choices, array $labels, array $preferredChoices)
    {
                foreach ($choices as $group => $choice) {
            if (!isset($labels[$group])) {
                throw new \InvalidArgumentException('The structures of the choices and labels array do not match.');
            }

            if (is_array($choice)) {
                                if (count($choice) > 0) {
                    $this->addChoiceGroup(
                        $group,
                        $bucketForPreferred,
                        $bucketForRemaining,
                        $choice,
                        $labels[$group],
                        $preferredChoices
                    );
                }
            } else {
                $this->addChoice(
                    $bucketForPreferred,
                    $bucketForRemaining,
                    $choice,
                    $labels[$group],
                    $preferredChoices
                );
            }
        }
    }

    
    protected function addChoiceGroup($group, array &$bucketForPreferred, array &$bucketForRemaining, array $choices, array $labels, array $preferredChoices)
    {
                        $bucketForPreferred[$group] = array();
        $bucketForRemaining[$group] = array();

        $this->addChoices(
            $bucketForPreferred[$group],
            $bucketForRemaining[$group],
            $choices,
            $labels,
            $preferredChoices
        );

                if (empty($bucketForPreferred[$group])) {
            unset($bucketForPreferred[$group]);
        }
        if (empty($bucketForRemaining[$group])) {
            unset($bucketForRemaining[$group]);
        }
    }

    
    protected function addChoice(array &$bucketForPreferred, array &$bucketForRemaining, $choice, $label, array $preferredChoices)
    {
        $index = $this->createIndex($choice);

        if ('' === $index || null === $index || !FormConfigBuilder::isValidName((string) $index)) {
            throw new InvalidConfigurationException('The index "' . $index . '" created by the choice list is invalid. It should be a valid, non-empty Form name.');
        }

        $value = $this->createValue($choice);

        if (!is_string($value)) {
            throw new InvalidConfigurationException('The value created by the choice list is of type "' . gettype($value) . '", but should be a string.');
        }

        $view = new ChoiceView($choice, $value, $label);

        $this->choices[$index] = $this->fixChoice($choice);
        $this->values[$index] = $value;

        if ($this->isPreferred($choice, $preferredChoices)) {
            $bucketForPreferred[$index] = $view;
        } else {
            $bucketForRemaining[$index] = $view;
        }
    }

    
    protected function isPreferred($choice, array $preferredChoices)
    {
        return false !== array_search($choice, $preferredChoices, true);
    }

    
    protected function createIndex($choice)
    {
        return count($this->choices);
    }

    
    protected function createValue($choice)
    {
        return (string) count($this->values);
    }

    
    protected function fixValue($value)
    {
        return (string) $value;
    }

    
    protected function fixValues(array $values)
    {
        foreach ($values as $i => $value) {
            $values[$i] = $this->fixValue($value);
        }

        return $values;
    }

    
    protected function fixIndex($index)
    {
        if (is_bool($index) || (string) (int) $index === (string) $index) {
            return (int) $index;
        }

        return (string) $index;
    }

    
    protected function fixIndices(array $indices)
    {
        foreach ($indices as $i => $index) {
            $indices[$i] = $this->fixIndex($index);
        }

        return $indices;
    }

    
    protected function fixChoice($choice)
    {
        return $choice;
    }

    
    protected function fixChoices(array $choices)
    {
        return $choices;
    }
}
}
 



namespace Symfony\Component\Form\Extension\Core\ChoiceList
{


class SimpleChoiceList extends ChoiceList
{
    
    public function __construct(array $choices, array $preferredChoices = array())
    {
                parent::__construct($choices, $choices, array_flip($preferredChoices));
    }

    
    public function getChoicesForValues(array $values)
    {
        $values = $this->fixValues($values);

                        return $this->fixChoices(array_intersect($values, $this->getValues()));
    }

    
    public function getValuesForChoices(array $choices)
    {
        $choices = $this->fixChoices($choices);

                        return $this->fixValues(array_intersect($choices, $this->getValues()));
    }

    
    protected function addChoices(array &$bucketForPreferred, array &$bucketForRemaining, $choices, array $labels, array $preferredChoices)
    {
                foreach ($choices as $choice => $label) {
            if (is_array($label)) {
                                if (count($label) > 0) {
                    $this->addChoiceGroup(
                        $choice,
                        $bucketForPreferred,
                        $bucketForRemaining,
                        $label,
                        $label,
                        $preferredChoices
                    );
                }
            } else {
                $this->addChoice(
                    $bucketForPreferred,
                    $bucketForRemaining,
                    $choice,
                    $label,
                    $preferredChoices
                );
            }
        }
    }

    
    protected function isPreferred($choice, array $preferredChoices)
    {
                return isset($preferredChoices[$choice]);
    }

    
    protected function fixChoice($choice)
    {
        return $this->fixIndex($choice);
    }

    
    protected function fixChoices(array $choices)
    {
        return $this->fixIndices($choices);
    }

    
    protected function createValue($choice)
    {
                        return (string) $choice;
    }
}
}
 



namespace Sonata\AdminBundle\Form\ChoiceList
{

use Symfony\Component\Form\Util\PropertyPath;
use Symfony\Component\Form\Exception\FormException;
use Symfony\Component\Form\Extension\Core\ChoiceList\SimpleChoiceList;
use Sonata\AdminBundle\Model\ModelManagerInterface;

class ModelChoiceList extends SimpleChoiceList
{
    
    private $modelManager;

    
    private $class;

    
    private $entities = array();

    
    private $query;

    
    private $identifier = array();

    
    private $reflProperties = array();

    private $propertyPath;

    
    public function __construct(ModelManagerInterface $modelManager, $class, $property = null, $query = null, $choices = array())
    {
        $this->modelManager   = $modelManager;
        $this->class          = $class;
        $this->query          = $query;
        $this->identifier     = $this->modelManager->getIdentifierFieldNames($this->class);

                        if ($property) {
            $this->propertyPath = new PropertyPath($property);
        }

        parent::__construct($this->load($choices));
    }

    
    protected function load($choices)
    {
        if (is_array($choices)) {
            $entities = $choices;
        } elseif ($this->query) {
            $entities = $this->modelManager->executeQuery($this->query);
        } else {
            $entities = $this->modelManager->findBy($this->class);
        }

        $choices = array();
        $this->entities = array();

        foreach ($entities as $key => $entity) {
            if ($this->propertyPath) {
                                $value = $this->propertyPath->getValue($entity);
            } else {
                                $value = (string) $entity;
            }

            if (count($this->identifier) > 1) {
                                                $choices[$key] = $value;
                $this->entities[$key] = $entity;
            } else {
                                                $id = current($this->getIdentifierValues($entity));
                $choices[$id] = $value;
                $this->entities[$id] = $entity;
            }
        }

        return $choices;
    }

    
    public function getIdentifier()
    {
        return $this->identifier;
    }

    
    public function getEntities()
    {
        return $this->entities;
    }

    
    public function getEntity($key)
    {

        if (count($this->identifier) > 1) {
                        $entities = $this->getEntities();

            return isset($entities[$key]) ? $entities[$key] : null;
        } elseif ($this->entities) {
            return isset($this->entities[$key]) ? $this->entities[$key] : null;
        }

        return $this->modelManager->find($this->class, $key);
    }

    
    private function getReflProperty($property)
    {
        if (!isset($this->reflProperties[$property])) {
            $this->reflProperties[$property] = new \ReflectionProperty($this->class, $property);
            $this->reflProperties[$property]->setAccessible(true);
        }

        return $this->reflProperties[$property];
    }

    
    public function getIdentifierValues($entity)
    {
        return $this->modelManager->getIdentifierValues($entity);
    }

    
    public function getModelManager()
    {
        return $this->modelManager;
    }

    
    public function getClass()
    {
        return $this->class;
    }
}
}
 



namespace Symfony\Component\Form
{


interface DataTransformerInterface
{
    
    public function transform($value);

    
    public function reverseTransform($value);
}
}
 



namespace Sonata\AdminBundle\Form\DataTransformer
{

use Symfony\Component\Form\DataTransformerInterface;

use Sonata\AdminBundle\Model\ModelManagerInterface;

class ArrayToModelTransformer implements DataTransformerInterface
{
    protected $modelManager;

    protected $className;

    
    public function __construct(ModelManagerInterface $modelManager, $className)
    {
        $this->modelManager = $modelManager;
        $this->className    = $className;
    }

    
    public function reverseTransform($array)
    {
                        if ($array instanceof $this->className) {
            return $array;
        }

        $instance = new $this->className;

        if (!is_array($array)) {
            return $instance;
        }

        return $this->modelManager->modelReverseTransform($this->className, $array);
    }

    
    public function transform($value)
    {
        return $value;
    }
}
}
 


namespace Sonata\AdminBundle\Form\DataTransformer
{

use Symfony\Component\Form\DataTransformerInterface;

use Sonata\AdminBundle\Model\ModelManagerInterface;


class ModelToIdTransformer implements DataTransformerInterface
{
    protected $modelManager;

    protected $className;

    
    public function __construct(ModelManagerInterface $modelManager, $className)
    {
        $this->modelManager = $modelManager;
        $this->className    = $className;
    }

    
    public function reverseTransform($newId)
    {
        if (empty($newId)) {
            return null;
        }

        return $this->modelManager->find($this->className, $newId);
    }

    
    public function transform($entity)
    {
        if (empty($entity)) {
            return null;
        }

        return current($this->modelManager->getIdentifierValues($entity));
    }
}
}
 



namespace Sonata\AdminBundle\Form\DataTransformer
{

use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\DataTransformerInterface;

use Sonata\AdminBundle\Form\ChoiceList\ModelChoiceList;

class ModelsToArrayTransformer implements DataTransformerInterface
{
    protected $choiceList;

    
    public function __construct(ModelChoiceList $choiceList)
    {
        $this->choiceList = $choiceList;
    }

    
    public function transform($collection)
    {
        if (null === $collection) {
            return array();
        }

        $array = array();

        if (count($this->choiceList->getIdentifier()) > 1) {
                        $availableEntities = $this->choiceList->getEntities();

            foreach ($collection as $entity) {
                                $key = array_search($entity, $availableEntities);
                $array[] = $key;
            }
        } else {
            foreach ($collection as $entity) {
                $array[] = current($this->choiceList->getIdentifierValues($entity));
            }
        }

        return $array;
    }

    
    public function reverseTransform($keys)
    {
        $collection = $this->choiceList->getModelManager()->getModelCollectionInstance(
            $this->choiceList->getClass()
        );

        if (!$collection instanceof \ArrayAccess) {
            throw new UnexpectedTypeException($collection, '\ArrayAccess');
        }

        if ('' === $keys || null === $keys) {
            return $collection;
        }

        if (!is_array($keys)) {
            throw new UnexpectedTypeException($keys, 'array');
        }

        $notFound = array();

                foreach ($keys as $key) {
            if ($entity = $this->choiceList->getEntity($key)) {
                $collection[] = $entity;
            } else {
                $notFound[] = $key;
            }
        }

        if (count($notFound) > 0) {
            throw new TransformationFailedException(sprintf('The entities with keys "%s" could not be found', implode('", "', $notFound)));
        }

        return $collection;
    }
}
}
 



namespace Symfony\Component\EventDispatcher
{


interface EventSubscriberInterface
{
    
    public static function getSubscribedEvents();
}
}
 



namespace Sonata\AdminBundle\Form\EventListener
{

use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Event\FilterDataEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Sonata\AdminBundle\Model\ModelManagerInterface;

class MergeCollectionListener implements EventSubscriberInterface
{
    protected $modelManager;

    
    public function __construct(ModelManagerInterface $modelManager)
    {
        $this->modelManager = $modelManager;
    }

    
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::BIND => array('onBind', 10),
        );
    }

    
    public function onBind(FilterDataEvent $event)
    {
        $collection = $event->getForm()->getData();
        $data       = $event->getData();

                $event->stopPropagation();

        if (!$collection) {
            $collection = $data;
        } elseif (count($data) === 0) {
            $this->modelManager->collectionClear($collection);
        } else {
                        foreach ($collection as $entity) {
                if (!$this->modelManager->collectionHasElement($data, $entity)) {
                    $this->modelManager->collectionRemoveElement($collection, $entity);
                } else {
                    $this->modelManager->collectionRemoveElement($data, $entity);
                }
            }

            foreach ($data as $entity) {
                $this->modelManager->collectionAddElement($collection, $entity);
            }
        }

        $event->setData($collection);
    }
}
}
 



namespace Sonata\AdminBundle\Form\EventListener
{

use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Event\DataEvent;
use Symfony\Component\Form\Event\FilterDataEvent;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


class ResizeFormListener implements EventSubscriberInterface
{
    
    private $factory;

    
    private $type;

    
    private $resizeOnBind;

    private $typeOptions;

    private $removed = array();

    
    public function __construct(FormFactoryInterface $factory, $type, array $typeOptions = array(), $resizeOnBind = false)
    {
        $this->factory      = $factory;
        $this->type         = $type;
        $this->resizeOnBind = $resizeOnBind;
        $this->typeOptions  = $typeOptions;
    }

    
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA    => 'preSetData',
            FormEvents::PRE_BIND        => 'preBind',
            FormEvents::BIND            => 'onBind',
        );
    }

    
    public function preSetData(DataEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();

        if (null === $data) {
            $data = array();
        }

        if (!is_array($data) && !$data instanceof \Traversable) {
            throw new UnexpectedTypeException($data, 'array or \Traversable');
        }

                foreach ($form as $name => $child) {
            $form->remove($name);
        }

                foreach ($data as $name => $value) {
            $options = array_merge($this->typeOptions, array(
                'property_path' => '[' . $name . ']',
            ));

            $form->add($this->factory->createNamed($name, $this->type, $value, $options));
        }
    }

    
    public function preBind(DataEvent $event)
    {
        if (!$this->resizeOnBind) {
            return;
        }

        $form = $event->getForm();
        $data = $event->getData();

        if (null === $data || '' === $data) {
            $data = array();
        }

        if (!is_array($data) && !$data instanceof \Traversable) {
            throw new UnexpectedTypeException($data, 'array or \Traversable');
        }

                foreach ($form as $name => $child) {
            $form->remove($name);
        }

                foreach ($data as $name => $value) {
            if (!$form->has($name)) {
                $options = array_merge($this->typeOptions, array(
                    'property_path' => '[' . $name . ']',
                ));

                $form->add($this->factory->createNamed($name, $this->type, null, $options));
            }

            if (isset($value['_delete'])) {
                $this->removed[] = $name;
            }
        }
    }

    
    public function onBind(FilterDataEvent $event)
    {
        if (!$this->resizeOnBind) {
            return;
        }

        $form = $event->getForm();
        $data = $event->getData();

        if (null === $data) {
            $data = array();
        }

        if (!is_array($data) && !$data instanceof \Traversable) {
            throw new UnexpectedTypeException($data, 'array or \Traversable');
        }

        foreach ($data as $name => $child) {
            if (!$form->has($name)) {
                unset($data[$name]);
            }
        }

                foreach ($this->removed as $pos) {
            unset($data[$pos]);
        }

        $event->setData($data);
    }
}
}
 



namespace Symfony\Component\Form
{

use Symfony\Component\OptionsResolver\OptionsResolverInterface;


interface FormTypeExtensionInterface
{
    
    public function buildForm(FormBuilderInterface $builder, array $options);

    
    public function buildView(FormView $view, FormInterface $form, array $options);

    
    public function finishView(FormView $view, FormInterface $form, array $options);

    
    public function setDefaultOptions(OptionsResolverInterface $resolver);

    
    public function getExtendedType();
}
}
 



namespace Symfony\Component\Form
{

use Symfony\Component\OptionsResolver\OptionsResolverInterface;


abstract class AbstractTypeExtension implements FormTypeExtensionInterface
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    }

    
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
    }

    
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
    }

    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults($this->getDefaultOptions());
        $resolver->addAllowedValues($this->getAllowedOptionValues());
    }

    
    public function getDefaultOptions()
    {
        return array();
    }

    
    public function getAllowedOptionValues()
    {
        return array();
    }
}
}
 



namespace Sonata\AdminBundle\Form\Extension\Field\Type
{

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Sonata\AdminBundle\Admin\FieldDescriptionInterface;
use Sonata\AdminBundle\Exception\NoValueException;

class FormTypeFieldExtension extends AbstractTypeExtension
{
    protected $defaultClasses = array();

    
    public function __construct(array $defaultClasses = array())
    {
        $this->defaultClasses = $defaultClasses;
    }

    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $sonataAdmin = array(
            'name'              => null,
            'admin'             => null,
            'value'             => null,
            'edit'              => 'standard',
            'inline'            => 'natural',
            'field_description' => null,
            'block_name'        => false
        );

        $builder->setAttribute('sonata_admin_enabled', false);

        if ($options['sonata_field_description'] instanceof FieldDescriptionInterface) {
            $fieldDescription = $options['sonata_field_description'];

            $sonataAdmin['admin']             = $fieldDescription->getAdmin();
            $sonataAdmin['field_description'] = $fieldDescription;
            $sonataAdmin['name']              = $fieldDescription->getName();
            $sonataAdmin['edit']              = $fieldDescription->getOption('edit', 'standard');
            $sonataAdmin['inline']            = $fieldDescription->getOption('inline', 'natural');
            $sonataAdmin['block_name']        = $fieldDescription->getOption('block_name', false);
            $sonataAdmin['class']             = $this->getClass($builder);

            $builder->setAttribute('sonata_admin_enabled', true);
        }

        $builder->setAttribute('sonata_admin', $sonataAdmin);
    }

    
    protected function getClass(FormBuilderInterface $formBuilder)
    {
        foreach ($formBuilder->getTypes() as $type) {
            if (isset($this->defaultClasses[$type->getName()])) {
                return $this->defaultClasses[$type->getName()];
            }
        }

        return '';
    }

    
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $sonataAdmin = $form->getConfig()->getAttribute('sonata_admin');

                if ($sonataAdmin && $form->getConfig()->getAttribute('sonata_admin_enabled', true)) {
            $sonataAdmin['value'] = $form->getData();

                        $block_prefixes    = $view->vars['block_prefixes'];
            $baseName = str_replace('.', '_', $sonataAdmin['admin']->getCode());
            $baseType = $block_prefixes[count($block_prefixes) - 2];
            $blockSuffix = preg_replace("#^_([a-z0-9]{14})_(.++)$#", "\$2", array_pop($block_prefixes));

            $block_prefixes[] = sprintf('%s_%s', $baseName, $baseType);
            $block_prefixes[] = sprintf('%s_%s_%s', $baseName, $sonataAdmin['name'], $baseType);
            $block_prefixes[] = sprintf('%s_%s_%s_%s', $baseName, $sonataAdmin['name'], $baseType, $blockSuffix);

            if (isset($sonataAdmin['block_name']) && $sonataAdmin['block_name'] !== false) {
                $block_prefixes[] = $sonataAdmin['block_name'];
            }

            $view->vars['block_prefixes'] = $block_prefixes;
            $view->vars['sonata_admin_enabled'] = true;
            $view->vars['sonata_admin'] = $sonataAdmin;

            $attr = $view->vars['attr'];

            if (!isset($attr['class']) && isset($sonataAdmin['class'])) {
                $attr['class'] = $sonataAdmin['class'];
            }

            $view->vars['attr'] = $attr;
        } else {
            $view->vars['sonata_admin_enabled'] = false;
        }

        $view->vars['sonata_admin'] = $sonataAdmin;
    }

    
    public function getExtendedType()
    {
        return 'field';
    }

    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'sonata_admin'             => null,
            'sonata_field_description' => null,
        ));
    }

    
    public function getValueFromFieldDescription($object, FieldDescriptionInterface $fieldDescription)
    {
        $value = null;

        if (!$object) {
            return $value;
        }

        try {
            $value = $fieldDescription->getValue($object);
        } catch (NoValueException $e) {
            if ($fieldDescription->getAssociationAdmin()) {
                $value = $fieldDescription->getAssociationAdmin()->getNewInstance();
            }
        }

        return $value;
    }
}
}
 

namespace Sonata\AdminBundle\Form
{

use Sonata\AdminBundle\Builder\FormContractorInterface;
use Sonata\AdminBundle\Admin\AdminInterface;
use Symfony\Component\Form\FormBuilder;


class FormMapper
{
    protected $formBuilder;

    protected $formContractor;

    protected $admin;

    protected $currentGroup;

    
    public function __construct(FormContractorInterface $formContractor, FormBuilder $formBuilder, AdminInterface $admin)
    {
        $this->formBuilder    = $formBuilder;
        $this->formContractor = $formContractor;
        $this->admin          = $admin;
    }

    
    public function with($name, array $options = array())
    {
        $formGroups = $this->admin->getFormGroups();
        if (!isset($formGroups[$name])) {
            $formGroups[$name] = array();
        }

        $formGroups[$name] = array_merge(array(
            'collapsed'   => false,
            'fields'      => array(),
            'description' => false
        ), $formGroups[$name], $options);

        $this->admin->setFormGroups($formGroups);

        $this->currentGroup = $name;

        return $this;
    }

    
    public function end()
    {
        $this->currentGroup = null;

        return $this;
    }

    
    public function reorder(array $keys)
    {
        if (!$this->currentGroup) {
            $this->with($this->admin->getLabel());
        }

        $this->admin->reorderFormGroup($this->currentGroup, $keys);

        return $this;
    }

    
    public function add($name, $type = null, array $options = array(), array $fieldDescriptionOptions = array())
    {
        if (!$this->currentGroup) {
            $this->with($this->admin->getLabel());
        }

        $label = $name instanceof FormBuilder ? $name->getName() : $name;

        $formGroups                                        = $this->admin->getFormGroups();
        $formGroups[$this->currentGroup]['fields'][$label] = $label;
        $this->admin->setFormGroups($formGroups);

        if (!isset($fieldDescriptionOptions['type']) && is_string($type)) {
            $fieldDescriptionOptions['type'] = $type;
        }

        $fieldDescription = $this->admin->getModelManager()->getNewFieldDescriptionInstance(
            $this->admin->getClass(),
            $name instanceof FormBuilder ? $name->getName() : $name,
            $fieldDescriptionOptions
        );

        $this->formContractor->fixFieldDescription($this->admin, $fieldDescription, $fieldDescriptionOptions);

        $this->admin->addFormFieldDescription($name instanceof FormBuilder ? $name->getName() : $name, $fieldDescription);

        if ($name instanceof FormBuilder) {
            $this->formBuilder->add($name);
        } else {
            $options = array_replace_recursive($this->formContractor->getDefaultOptions($type, $fieldDescription), $options);

            if (!isset($options['label'])) {
                $options['label'] = $this->admin->getLabelTranslatorStrategy()->getLabel($fieldDescription->getName(), 'form', 'label');
            }

            $help = null;
            if (isset($options['help'])) {
                $help = $options['help'];
                unset($options['help']);
            }

            $this->formBuilder->add($name, $type, $options);

            if (null !== $help) {
                $this->admin->getFormFieldDescription($name)->setHelp($help);
            }
        }

        return $this;
    }

    
    public function get($name)
    {
        return $this->formBuilder->get($name);
    }

    
    public function has($key)
    {
        return $this->formBuilder->has($key);
    }

    
    public function remove($key)
    {
        $this->admin->removeFormFieldDescription($key);
        $this->formBuilder->remove($key);

        return $this;
    }

    
    public function getFormBuilder()
    {
        return $this->formBuilder;
    }

    
    public function getAdmin()
    {
        return $this->admin;
    }

    
    public function create($name, $type = null, array $options = array())
    {
        return $this->formBuilder->create($name, $type, $options);
    }

    
    public function setHelps(array $helps = array())
    {
        foreach ($helps as $name => $help) {
            if ($this->admin->hasFormFieldDescription($name)) {
                $this->admin->getFormFieldDescription($name)->setHelp($help);
            }
        }

        return $this;
    }
}
}
 



namespace Symfony\Component\Form
{

use Symfony\Component\OptionsResolver\OptionsResolverInterface;


interface FormTypeInterface
{
    
    public function buildForm(FormBuilderInterface $builder, array $options);

    
    public function buildView(FormView $view, FormInterface $form, array $options);

    
    public function finishView(FormView $view, FormInterface $form, array $options);

    
    public function setDefaultOptions(OptionsResolverInterface $resolver);

    
    public function getParent();

    
    public function getName();
}
}
 



namespace Symfony\Component\Form
{

use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


abstract class AbstractType implements FormTypeInterface
{
    
    private $extensions = array();

    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    }

    
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
    }

    
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
    }

    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults($this->getDefaultOptions(array()));
        $resolver->addAllowedValues($this->getAllowedOptionValues(array()));
    }

    
    public function getDefaultOptions(array $options)
    {
        return array();
    }

    
    public function getAllowedOptionValues(array $options)
    {
        return array();
    }

    
    public function getParent()
    {
        return 'form';
    }

    
    public function setExtensions(array $extensions)
    {
        $this->extensions = $extensions;
    }

    
    public function getExtensions()
    {
        return $this->extensions;
    }
}
}
 


namespace Sonata\AdminBundle\Form\Type
{

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Sonata\AdminBundle\Form\DataTransformer\ArrayToModelTransformer;

class AdminType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $admin = $this->getAdmin($options);

        if ($options['delete'] && $admin->isGranted('DELETE') ) {
            $builder->add('_delete', 'checkbox', array('required' => false, 'property_path' => false));
        }

        if (!$admin->hasSubject()) {
            $admin->setSubject($builder->getData());
        }

        $admin->defineFormBuilder($builder);

        $builder->prependClientTransformer(new ArrayToModelTransformer($admin->getModelManager(), $admin->getClass()));
    }

    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array('delete' => true));
    }

    
    public function getFieldDescription(array $options)
    {
        if (!isset($options['sonata_field_description'])) {
            throw new \RuntimeException('Please provide a valid `sonata_field_description` option');
        }

        return $options['sonata_field_description'];
    }

    
    public function getAdmin(array $options)
    {
        return $this->getFieldDescription($options)->getAssociationAdmin();
    }

    
    public function getName()
    {
        return 'sonata_type_admin';
    }
}
}
 


namespace Sonata\AdminBundle\Form\Type
{

use Symfony\Component\Form\AbstractType;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BooleanType extends AbstractType
{
    const TYPE_YES = 1;

    const TYPE_NO = 2;

    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'catalogue' => 'SonataAdminBundle',
            'choices'   => array(
                self::TYPE_YES  => 'label_type_yes',
                self::TYPE_NO   => 'label_type_no'
            )
        ));
    }

    
    public function getParent()
    {
        return 'sonata_type_translatable_choice';
    }

    
    public function getName()
    {
        return 'sonata_type_boolean';
    }
}
}
 



namespace Sonata\AdminBundle\Form\Type
{

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Sonata\AdminBundle\Form\EventListener\ResizeFormListener;

class CollectionType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $listener = new ResizeFormListener(
            $builder->getFormFactory(),
            $options['type'],
            $options['type_options'],
            $options['modifiable']
        );

        $builder->addEventSubscriber($listener);
    }

    
    public function getParent()
    {
        return 'field';
    }

    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'modifiable'    => false,
            'type'          => 'text',
            'type_options'  => array()
        ));
    }

    
    public function getName()
    {
        return 'sonata_type_collection';
    }
}
}
 


namespace Sonata\AdminBundle\Form\Type
{

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DateRangeType extends AbstractType
{
    protected $translator;

    
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('start', 'date', array_merge(array('required' => false), $options['field_options']));
        $builder->add('end', 'date', array_merge(array('required' => false), $options['field_options']));
    }

    
    public function getName()
    {
        return 'sonata_type_date_range';
    }

    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'field_options'    => array()
        ));
    }
}
}
 


namespace Sonata\AdminBundle\Form\Type
{

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DateTimeRangeType extends AbstractType
{
    protected $translator;

    
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('start', 'datetime', array_merge(array('required' => false), $options['field_options']));
        $builder->add('end', 'datetime', array_merge(array('required' => false), $options['field_options']));
    }

    
    public function getName()
    {
        return 'sonata_type_datetime_range';
    }

    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'field_options'    => array()
        ));
    }
}
}
 



namespace Symfony\Component\Form\Extension\Core\Type
{

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\Exception\FormException;
use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\ChoiceList\SimpleChoiceList;
use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceListInterface;
use Symfony\Component\Form\Extension\Core\EventListener\FixRadioInputListener;
use Symfony\Component\Form\Extension\Core\EventListener\FixCheckboxInputListener;
use Symfony\Component\Form\Extension\Core\EventListener\MergeCollectionListener;
use Symfony\Component\Form\Extension\Core\DataTransformer\ChoiceToValueTransformer;
use Symfony\Component\Form\Extension\Core\DataTransformer\ChoiceToBooleanArrayTransformer;
use Symfony\Component\Form\Extension\Core\DataTransformer\ChoicesToValuesTransformer;
use Symfony\Component\Form\Extension\Core\DataTransformer\ChoicesToBooleanArrayTransformer;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ChoiceType extends AbstractType
{
    
    private $choiceListCache = array();

    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (!$options['choice_list'] && !is_array($options['choices']) && !$options['choices'] instanceof \Traversable) {
            throw new FormException('Either the option "choices" or "choice_list" must be set.');
        }

        if ($options['expanded']) {
            $this->addSubForms($builder, $options['choice_list']->getPreferredViews(), $options);
            $this->addSubForms($builder, $options['choice_list']->getRemainingViews(), $options);

            if ($options['multiple']) {
                $builder
                    ->addViewTransformer(new ChoicesToBooleanArrayTransformer($options['choice_list']))
                    ->addEventSubscriber(new FixCheckboxInputListener($options['choice_list']), 10)
                ;
            } else {
                $builder
                    ->addViewTransformer(new ChoiceToBooleanArrayTransformer($options['choice_list']))
                    ->addEventSubscriber(new FixRadioInputListener($options['choice_list']), 10)
                ;
            }
        } else {
            if ($options['multiple']) {
                $builder->addViewTransformer(new ChoicesToValuesTransformer($options['choice_list']));
            } else {
                $builder->addViewTransformer(new ChoiceToValueTransformer($options['choice_list']));
            }
        }

        if ($options['multiple'] && $options['by_reference']) {
                                    $builder->addEventSubscriber(new MergeCollectionListener(true, true));
        }
    }

    
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars = array_replace($view->vars, array(
            'multiple'          => $options['multiple'],
            'expanded'          => $options['expanded'],
            'preferred_choices' => $options['choice_list']->getPreferredViews(),
            'choices'           => $options['choice_list']->getRemainingViews(),
            'separator'         => '-------------------',
            'empty_value'       => null,
        ));

                                        if ($options['multiple']) {
            $view->vars['is_selected'] = function ($choice, array $values) {
                return false !== array_search($choice, $values, true);
            };
        } else {
            $view->vars['is_selected'] = function ($choice, $value) {
                return $choice === $value;
            };
        }

                        if (0 === count($options['choice_list']->getIndicesForValues(array('')))) {
            $view->vars['empty_value'] = $options['empty_value'];
        }

        if ($options['multiple'] && !$options['expanded']) {
                                                $view->vars['full_name'] = $view->vars['full_name'].'[]';
        }
    }

    
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        if ($options['expanded']) {
                        $childName = $view->vars['full_name'];

                        if ($options['multiple']) {
                $childName .= '[]';
            }

            foreach ($view as $childView) {
                $childView->vars['full_name'] = $childName;
            }
        }
    }

    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $choiceListCache =& $this->choiceListCache;

        $choiceList = function (Options $options) use (&$choiceListCache) {
                        $choices = null !== $options['choices'] ? $options['choices'] : array();

                        $hash = md5(json_encode(array($choices, $options['preferred_choices'])));

            if (!isset($choiceListCache[$hash])) {
                $choiceListCache[$hash] = new SimpleChoiceList($choices, $options['preferred_choices']);
            }

            return $choiceListCache[$hash];
        };

        $emptyData = function (Options $options) {
            if ($options['multiple'] || $options['expanded']) {
                return array();
            }

            return '';
        };

        $emptyValue = function (Options $options) {
            return $options['required'] ? null : '';
        };

        $emptyValueNormalizer = function (Options $options, $emptyValue) {
            if ($options['multiple'] || $options['expanded']) {
                                return null;
            } elseif (false === $emptyValue) {
                                return null;
            }

                        return $emptyValue;
        };

        $compound = function (Options $options) {
            return $options['expanded'];
        };

        $resolver->setDefaults(array(
            'multiple'          => false,
            'expanded'          => false,
            'choice_list'       => $choiceList,
            'choices'           => array(),
            'preferred_choices' => array(),
            'empty_data'        => $emptyData,
            'empty_value'       => $emptyValue,
            'error_bubbling'    => false,
            'compound'          => $compound,
        ));

        $resolver->setNormalizers(array(
            'empty_value' => $emptyValueNormalizer,
        ));

        $resolver->setAllowedTypes(array(
            'choice_list' => array('null', 'Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceListInterface'),
        ));
    }

    
    public function getParent()
    {
        return 'field';
    }

    
    public function getName()
    {
        return 'choice';
    }

    
    private function addSubForms(FormBuilderInterface $builder, array $choiceViews, array $options)
    {
        foreach ($choiceViews as $i => $choiceView) {
            if (is_array($choiceView)) {
                                $this->addSubForms($builder, $choiceView, $options);
            } else {
                $choiceOpts = array(
                    'value' => $choiceView->value,
                    'label' => $choiceView->label,
                    'translation_domain' => $options['translation_domain'],
                );

                if ($options['multiple']) {
                    $choiceType = 'checkbox';
                                                            $choiceOpts['required'] = false;
                } else {
                    $choiceType = 'radio';
                }

                $builder->add((string) $i, $choiceType, $choiceOpts);
            }
        }
    }
}
}
 


namespace Sonata\AdminBundle\Form\Type
{

use Symfony\Component\Form\Extension\Core\Type\ChoiceType as FormChoiceType;
use Symfony\Component\Translation\TranslatorInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EqualType extends FormChoiceType
{
    const TYPE_IS_EQUAL = 1;

    const TYPE_IS_NOT_EQUAL = 2;

    protected $translator;

    
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setDefaults(array(
            'choices' => array(
                self::TYPE_IS_EQUAL     => $this->translator->trans('label_type_equals', array(), 'SonataAdminBundle'),
                self::TYPE_IS_NOT_EQUAL => $this->translator->trans('label_type_not_equals', array(), 'SonataAdminBundle'),
            )
        ));
    }

    
    public function getParent()
    {
        return 'choice';
    }

    
    public function getName()
    {
        return 'sonata_type_equal';
    }
}
}
 


namespace Sonata\AdminBundle\Form\Type\Filter
{

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Translation\TranslatorInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ChoiceType extends AbstractType
{
    const TYPE_CONTAINS = 1;

    const TYPE_NOT_CONTAINS = 2;

    const TYPE_EQUAL = 3;

    protected $translator;

    
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    
    public function getName()
    {
        return 'sonata_type_filter_choice';
    }

    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choices = array(
            self::TYPE_CONTAINS        => $this->translator->trans('label_type_contains', array(), 'SonataAdminBundle'),
            self::TYPE_NOT_CONTAINS    => $this->translator->trans('label_type_not_contains', array(), 'SonataAdminBundle'),
            self::TYPE_EQUAL           => $this->translator->trans('label_type_equals', array(), 'SonataAdminBundle'),
        );

        $builder
            ->add('type', 'choice', array('choices' => $choices, 'required' => false))
            ->add('value', $options['field_type'], array_merge(array('required' => false), $options['field_options']))
        ;
    }

    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'field_type'       => 'choice',
            'field_options'    => array()
        ));
    }
}
}
 


namespace Sonata\AdminBundle\Form\Type\Filter
{

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Translation\TranslatorInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DateRangeType extends AbstractType
{
    const TYPE_BETWEEN = 1;
    const TYPE_NOT_BETWEEN = 2;

    protected $translator;

    
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    
    public function getName()
    {
        return 'sonata_type_filter_date_range';
    }

    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choices = array(
            self::TYPE_BETWEEN    => $this->translator->trans('label_date_type_between', array(), 'SonataAdminBundle'),
            self::TYPE_NOT_BETWEEN    => $this->translator->trans('label_date_type_not_between', array(), 'SonataAdminBundle'),
        );

        $builder
            ->add('type', 'choice', array('choices' => $choices, 'required' => false))
            ->add('value', 'sonata_type_date_range', array('field_options' => $options['field_options']))
        ;
    }

    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'field_type'       => 'sonata_type_date_range',
            'field_options'    => array('format' => 'yyyy-MM-dd')
        ));
    }
}
}
 


namespace Sonata\AdminBundle\Form\Type\Filter
{

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Translation\TranslatorInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DateTimeRangeType extends AbstractType
{
    const TYPE_BETWEEN = 1;
    const TYPE_NOT_BETWEEN = 2;

    protected $translator;

    
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    
    public function getName()
    {
        return 'sonata_type_filter_datetime_range';
    }

    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choices = array(
            self::TYPE_BETWEEN    => $this->translator->trans('label_date_type_between', array(), 'SonataAdminBundle'),
            self::TYPE_NOT_BETWEEN    => $this->translator->trans('label_date_type_not_between', array(), 'SonataAdminBundle'),
        );

        $builder
            ->add('type', 'choice', array('choices' => $choices, 'required' => false))
            ->add('value', 'sonata_type_datetime_range', array('field_options' => $options['field_options']))
        ;
    }

    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'field_type'       => 'sonata_type_datetime_range',
            'field_options'    => array('date_format' => 'yyyy-MM-dd')
        ));
    }
}
}
 


namespace Sonata\AdminBundle\Form\Type\Filter
{

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Translation\TranslatorInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DateTimeType extends AbstractType
{
    const TYPE_GREATER_EQUAL = 1;

    const TYPE_GREATER_THAN = 2;

    const TYPE_EQUAL = 3;

    const TYPE_LESS_EQUAL = 4;

    const TYPE_LESS_THAN = 5;

    const TYPE_NULL = 6;

    const TYPE_NOT_NULL = 7;

    protected $translator;

    
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    
    public function getName()
    {
        return 'sonata_type_filter_datetime';
    }

    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choices = array(
            self::TYPE_EQUAL            => $this->translator->trans('label_date_type_equal', array(), 'SonataAdminBundle'),
            self::TYPE_GREATER_EQUAL    => $this->translator->trans('label_date_type_greater_equal', array(), 'SonataAdminBundle'),
            self::TYPE_GREATER_THAN     => $this->translator->trans('label_date_type_greater_than', array(), 'SonataAdminBundle'),
            self::TYPE_LESS_EQUAL       => $this->translator->trans('label_date_type_less_equal', array(), 'SonataAdminBundle'),
            self::TYPE_LESS_THAN        => $this->translator->trans('label_date_type_less_than', array(), 'SonataAdminBundle'),
            self::TYPE_NULL             => $this->translator->trans('label_date_type_null', array(), 'SonataAdminBundle'),
            self::TYPE_NOT_NULL         => $this->translator->trans('label_date_type_not_null', array(), 'SonataAdminBundle'),
        );

        $builder
            ->add('type', 'choice', array('choices' => $choices, 'required' => false))
            ->add('value', 'datetime', array_merge(array('required' => false), $options['field_options']))
        ;
    }

    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'field_type'       => 'datetime',
            'field_options'    => array('date_format' => 'yyyy-MM-dd')
        ));
    }
}
}
 


namespace Sonata\AdminBundle\Form\Type\Filter
{

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Translation\TranslatorInterface;

use Symfony\Component\Optionsresolver\OptionsResolverInterface;

class DateType extends AbstractType
{
    const TYPE_GREATER_EQUAL = 1;

    const TYPE_GREATER_THAN = 2;

    const TYPE_EQUAL = 3;

    const TYPE_LESS_EQUAL = 4;

    const TYPE_LESS_THAN = 5;

    const TYPE_NULL = 6;

    const TYPE_NOT_NULL = 7;

    protected $translator;

    
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    
    public function getName()
    {
        return 'sonata_type_filter_date';
    }

    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choices = array(
            self::TYPE_EQUAL            => $this->translator->trans('label_date_type_equal', array(), 'SonataAdminBundle'),
            self::TYPE_GREATER_EQUAL    => $this->translator->trans('label_date_type_greater_equal', array(), 'SonataAdminBundle'),
            self::TYPE_GREATER_THAN     => $this->translator->trans('label_date_type_greater_than', array(), 'SonataAdminBundle'),
            self::TYPE_LESS_EQUAL       => $this->translator->trans('label_date_type_less_equal', array(), 'SonataAdminBundle'),
            self::TYPE_LESS_THAN        => $this->translator->trans('label_date_type_less_than', array(), 'SonataAdminBundle'),
            self::TYPE_NULL             => $this->translator->trans('label_date_type_null', array(), 'SonataAdminBundle'),
            self::TYPE_NOT_NULL         => $this->translator->trans('label_date_type_not_null', array(), 'SonataAdminBundle'),
        );

        $builder
            ->add('type', 'choice', array('choices' => $choices, 'required' => false))
            ->add('value', 'date', array_merge(array('required' => false), $options['field_options']))
        ;
    }

    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'field_type'       => 'date',
            'field_options'    => array('date_format' => 'yyyy-MM-dd')
        ));
    }
}
}
 


namespace Sonata\AdminBundle\Form\Type\Filter
{

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DefaultType extends AbstractType
{
    
    public function getName()
    {
        return 'sonata_type_filter_default';
    }

    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', $options['operator_type'], array_merge(array('required' => false), $options['operator_options']))
            ->add('value', $options['field_type'], array_merge(array('required' => false), $options['field_options']))
        ;
    }

    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'operator_type'    => 'hidden',
            'operator_options' => array(),
            'field_type'       => 'text',
            'field_options'    => array()
        ));
    }
}
}
 


namespace Sonata\AdminBundle\Form\Type\Filter
{

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Translation\TranslatorInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NumberType extends AbstractType
{
    const TYPE_GREATER_EQUAL = 1;

    const TYPE_GREATER_THAN = 2;

    const TYPE_EQUAL = 3;

    const TYPE_LESS_EQUAL = 4;

    const TYPE_LESS_THAN = 5;

    protected $translator;

    
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    
    public function getName()
    {
        return 'sonata_type_filter_number';
    }

    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choices = array(
            self::TYPE_EQUAL            => $this->translator->trans('label_type_equal', array(), 'SonataAdminBundle'),
            self::TYPE_GREATER_EQUAL    => $this->translator->trans('label_type_greater_equal', array(), 'SonataAdminBundle'),
            self::TYPE_GREATER_THAN     => $this->translator->trans('label_type_greater_than', array(), 'SonataAdminBundle'),
            self::TYPE_LESS_EQUAL       => $this->translator->trans('label_type_less_equal', array(), 'SonataAdminBundle'),
            self::TYPE_LESS_THAN        => $this->translator->trans('label_type_less_than', array(), 'SonataAdminBundle'),
        );

        $builder
            ->add('type', 'choice', array('choices' => $choices, 'required' => false))
            ->add('value', $options['field_type'], array_merge(array('required' => false), $options['field_options']))
        ;
    }

    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'field_type'    => 'number',
            'field_options' => array(),
        ));
    }
}
}
 


namespace Sonata\AdminBundle\Form\Type
{

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ImmutableArrayType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        foreach ($options['keys'] as $infos) {
            if ($infos instanceof FormBuilderInterface) {
                $builder->add($infos);
            } else {
                list($name, $type, $options) = $infos;
                $builder->add($name, $type, $options);
            }
        }
    }

    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'keys'    => array(),
        ));
    }

    
    public function getName()
    {
        return 'sonata_type_immutable_array';
    }
}
}
 



namespace Sonata\AdminBundle\Form\Type
{

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\Options;

use Sonata\AdminBundle\Form\DataTransformer\ModelToIdTransformer;

class ModelReferenceType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new ModelToIdTransformer($options['model_manager'], $options['class']));
    }

    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'compound'      => false,
            'model_manager' => null,
            'class'         => null,
            'parent'        => 'text'
        ));
    }

    
    public function getParent()
    {
        return 'text';
    }

    
    public function getName()
    {
        return 'sonata_type_model_reference';
    }
}
}
 



namespace Sonata\AdminBundle\Form\Type
{

use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceListInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Sonata\AdminBundle\Form\EventListener\MergeCollectionListener;
use Sonata\AdminBundle\Form\ChoiceList\ModelChoiceList;
use Sonata\AdminBundle\Form\DataTransformer\ModelsToArrayTransformer;
use Sonata\AdminBundle\Form\DataTransformer\ModelToIdTransformer;


class ModelType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['multiple']) {
            $builder
                ->addEventSubscriber(new MergeCollectionListener($options['model_manager']))
                ->addViewTransformer(new ModelsToArrayTransformer($options['choice_list']), true);
        } else {
            $builder
                ->addViewTransformer(new ModelToIdTransformer($options['model_manager'], $options['class']), true)
            ;
        }
    }

    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'compound'          => function (Options $options) {
                return isset($options['multiple']) ? $options['multiple'] : false;
            },

            'template'          => 'choice',
            'multiple'          => false,
            'expanded'          => false,
            'model_manager'     => null,
            'class'             => null,
            'property'          => null,
            'query'             => null,
            'choices'           => null,
            'preferred_choices' => array(),
            'choice_list'       => function (Options $options, $previousValue) {
                if ($previousValue instanceof ChoiceListInterface && count($choices = $previousValue->getChoices())) {
                    return $choices;
                }

                return new ModelChoiceList(
                    $options['model_manager'],
                    $options['class'],
                    $options['property'],
                    $options['query'],
                    $options['choices']
                );
            }
        ));
    }

    
    public function getParent()
    {
        return 'choice';
    }

    
    public function getName()
    {
        return 'sonata_type_model';
    }
}
}
 



namespace Sonata\AdminBundle\Form\Type
{

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Sonata\AdminBundle\Form\DataTransformer\ModelToIdTransformer;


class ModelTypeList extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->resetViewTransformers()
            ->addViewTransformer(new ModelToIdTransformer($options['model_manager'], $options['class']));
    }

    
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (isset($view->vars['sonata_admin'])) {
                        $view->vars['sonata_admin']['edit'] = 'list';
        }
    }

    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'model_manager'     => null,
            'class'             => null,
            'parent'            => 'text',
        ));
    }

    
    public function getParent()
    {
        return 'text';
    }

    
    public function getName()
    {
        return 'sonata_type_model_list';
    }
}
}
 



namespace Sonata\AdminBundle\Form\Type
{

use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TranslatableChoiceType extends AbstractType
{
    protected $translator;

    
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'catalogue' => 'messages',
        ));
    }

    
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['translation_domain'] = $options['catalogue'];
    }

    
    public function getParent()
    {
        return 'choice';
    }

    
    public function getName()
    {
        return 'sonata_type_translatable_choice';
    }
}
}
 



namespace Sonata\AdminBundle\Guesser
{

use Sonata\AdminBundle\Model\ModelManagerInterface;

interface TypeGuesserInterface
{
    
    public function guessType($class, $property, ModelManagerInterface $modelManager);
}
}
 



namespace Sonata\AdminBundle\Guesser
{

use Sonata\AdminBundle\Guesser\TypeGuesserInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Guess\Guess;
use Sonata\AdminBundle\Model\ModelManagerInterface;


class TypeGuesserChain implements TypeGuesserInterface
{
    protected $guessers = array();

    
    public function __construct(array $guessers)
    {
        foreach ($guessers as $guesser) {
            if (!$guesser instanceof TypeGuesserInterface) {
                throw new UnexpectedTypeException($guesser, 'Sonata\AdminBundle\Guesser\TypeGuesserInterface');
            }

            if ($guesser instanceof self) {
                $this->guessers = array_merge($this->guessers, $guesser->guessers);
            } else {
                $this->guessers[] = $guesser;
            }
        }
    }

    
    public function guessType($class, $property, ModelManagerInterface $modelManager)
    {
        return $this->guess(function ($guesser) use ($class, $property, $modelManager) {
            return $guesser->guessType($class, $property, $modelManager);
        });
    }

    
    private function guess(\Closure $closure)
    {
        $guesses = array();

        foreach ($this->guessers as $guesser) {
            if ($guess = $closure($guesser)) {
                $guesses[] = $guess;
            }
        }

        return Guess::getBestGuess($guesses);
    }
}
}
 



namespace Sonata\AdminBundle\Model
{

interface AuditManagerInterface
{
    
    public function setReader($serviceId, array $classes);

    
    public function hasReader($class);

    
    public function getReader($class);
}
}
 



namespace Sonata\AdminBundle\Model
{

use Symfony\Component\DependencyInjection\ContainerInterface;

class AuditManager implements AuditManagerInterface
{
    protected $classes = array();

    protected $readers = array();

    protected $container;

    
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    
    public function setReader($serviceId, array $classes)
    {
        $this->readers[$serviceId] = $classes;
    }

    
    public function hasReader($class)
    {
        foreach ($this->readers as $classes) {
            if (in_array($class, $classes)) {
                return true;
            }
        }

        return false;
    }

    
    public function getReader($class)
    {
        foreach ($this->readers as $readerId => $classes) {
            if (in_array($class, $classes)) {
                return $this->container->get($readerId);
            }
        }

        throw new \RuntimeException(sprintf('The class %s does not have any reader manager', $class));
    }
}
}
 



namespace Sonata\AdminBundle\Model
{

interface AuditReaderInterface
{
    
    public function find($className, $id, $revision);

    
    public function findRevisionHistory($className, $limit = 20, $offset = 0);

    
    public function findRevision($classname, $revision);

    
    public function findRevisions($className, $id);
}
}
 



namespace Sonata\AdminBundle\Model
{

use Sonata\AdminBundle\Admin\FieldDescriptionInterface;
use Sonata\AdminBundle\Datagrid\DatagridInterface;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;


interface ModelManagerInterface
{
    
    public function getNewFieldDescriptionInstance($class, $name, array $options = array());

    
    public function create($object);

    
    public function update($object);

    
    public function delete($object);

    
    public function findBy($class, array $criteria = array());

    
    public function findOneBy($class, array $criteria = array());

    
    public function find($class, $id);

    
    public function batchDelete($class, ProxyQueryInterface $queryProxy);

    
    public function getParentFieldDescription($parentAssociationMapping, $class);

    
    public function createQuery($class, $alias = 'o');

    
    public function getModelIdentifier($class);

    
    public function getIdentifierValues($model);

    
    public function getIdentifierFieldNames($class);

    
    public function getNormalizedIdentifier($model);

    
    public function getUrlsafeIdentifier($model);

    
    public function getModelInstance($class);

    
    public function getModelCollectionInstance($class);

    
    public function collectionRemoveElement(&$collection, &$element);

    
    public function collectionAddElement(&$collection, &$element);

    
    public function collectionHasElement(&$collection, &$element);

    
    public function collectionClear(&$collection);

    
    public function getSortParameters(FieldDescriptionInterface $fieldDescription, DatagridInterface $datagrid);

    
    public function getDefaultSortValues($class);

    
    public function modelReverseTransform($class, array $array = array());

    
    public function modelTransform($class, $instance);

    
    public function executeQuery($query);

    
    public function getDataSourceIterator(DatagridInterface $datagrid, array $fields, $firstResult = null, $maxResult = null);

    
    public function getExportFields($class);

    
    public function getPaginationParameters(DatagridInterface $datagrid, $page);

    
    public function addIdentifiersToQuery($class, ProxyQueryInterface $query, array $idx);
}
}
 



namespace Symfony\Component\Config\Loader
{


interface LoaderInterface
{
    
    public function load($resource, $type = null);

    
    public function supports($resource, $type = null);

    
    public function getResolver();

    
    public function setResolver(LoaderResolverInterface $resolver);

}
}
 



namespace Symfony\Component\Config\Loader
{

use Symfony\Component\Config\Exception\FileLoaderLoadException;


abstract class Loader implements LoaderInterface
{
    protected $resolver;

    
    public function getResolver()
    {
        return $this->resolver;
    }

    
    public function setResolver(LoaderResolverInterface $resolver)
    {
        $this->resolver = $resolver;
    }

    
    public function import($resource, $type = null)
    {
        $this->resolve($resource)->load($resource, $type);
    }

    
    public function resolve($resource, $type = null)
    {
        if ($this->supports($resource, $type)) {
            return $this;
        }

        $loader = null === $this->resolver ? false : $this->resolver->resolve($resource, $type);

        if (false === $loader) {
            throw new FileLoaderLoadException($resource);
        }

        return $loader;
    }
}
}
 



namespace Symfony\Component\Config\Loader
{

use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\Config\Exception\FileLoaderLoadException;
use Symfony\Component\Config\Exception\FileLoaderImportCircularReferenceException;


abstract class FileLoader extends Loader
{
    protected static $loading = array();

    protected $locator;

    private $currentDir;

    
    public function __construct(FileLocatorInterface $locator)
    {
        $this->locator = $locator;
    }

    public function setCurrentDir($dir)
    {
        $this->currentDir = $dir;
    }

    public function getLocator()
    {
        return $this->locator;
    }

    
    public function import($resource, $type = null, $ignoreErrors = false, $sourceResource = null)
    {
        try {
            $loader = $this->resolve($resource, $type);

            if ($loader instanceof FileLoader && null !== $this->currentDir) {
                $resource = $this->locator->locate($resource, $this->currentDir);
            }

            if (isset(self::$loading[$resource])) {
                throw new FileLoaderImportCircularReferenceException(array_keys(self::$loading));
            }
            self::$loading[$resource] = true;

            $ret = $loader->load($resource, $type);

            unset(self::$loading[$resource]);

            return $ret;
        } catch (FileLoaderImportCircularReferenceException $e) {
            throw $e;
        } catch (\Exception $e) {
            if (!$ignoreErrors) {
                                if ($e instanceof FileLoaderLoadException) {
                    throw $e;
                }

                throw new FileLoaderLoadException($resource, $sourceResource, null, $e);
            }
        }
    }
}
}
 


namespace Sonata\AdminBundle\Route
{

use Symfony\Component\Routing\RouteCollection as SymfonyRouteCollection;
use Symfony\Component\Routing\Route;

use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Config\Resource\FileResource;

use Sonata\AdminBundle\Admin\Pool;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AdminPoolLoader extends FileLoader
{
    
    protected $pool;

    
    protected $adminServiceIds = array();

    protected $container;

    
    public function __construct(Pool $pool, $adminServiceIds, ContainerInterface $container)
    {
        $this->pool             = $pool;
        $this->adminServiceIds  = $adminServiceIds;
        $this->container        = $container;
    }

    
    public function supports($resource, $type = null)
    {
        if ($type == 'sonata_admin') {
            return true;
        }

        return false;
    }

    
    public function load($resource, $type = null)
    {
        $collection = new SymfonyRouteCollection;
        foreach ($this->adminServiceIds as $id) {

            $admin = $this->pool->getInstance($id);

            foreach ($admin->getRoutes()->getElements() as $code => $route) {
                $collection->add($route->getDefault('_sonata_name'), $route);
            }

            $reflection = new \ReflectionObject($admin);
            $collection->addResource(new FileResource($reflection->getFileName()));
        }

        $reflection = new \ReflectionObject($this->container);
        $collection->addResource(new FileResource($reflection->getFileName()));

        return $collection;
    }
}
}
 

namespace Sonata\AdminBundle\Route
{

use Sonata\AdminBundle\Admin\AdminInterface;

interface RouteGeneratorInterface
{
    
    public function generateUrl(AdminInterface $admin, $name, array $parameters = array(), $absolute = false);

    
    public function generate($name, array $parameters = array(), $absolute = false);
}
}
 

namespace Sonata\AdminBundle\Route
{

use Sonata\AdminBundle\Admin\AdminInterface;
use Symfony\Component\Routing\RouterInterface;

class DefaultRouteGenerator implements RouteGeneratorInterface
{
    private $router;

    
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    
    public function generate($name, array $parameters = array(), $absolute = false)
    {
        return $this->router->generate($name, $parameters, $absolute);
    }

    
    public function generateUrl(AdminInterface $admin, $name, array $parameters = array(), $absolute = false)
    {
        if (!$admin->isChild()) {
            if (strpos($name, '.')) {
                $name = $admin->getCode().'|'.$name;
            } else {
                $name = $admin->getCode().'.'.$name;
            }
        }
                else if ($admin->isChild()) {
            $name = $admin->getBaseCodeRoute().'.'.$name;

                                    if (isset($parameters['id'])) {
                $parameters[$admin->getIdParameter()] = $parameters['id'];
                unset($parameters['id']);
            }

            $parameters[$admin->getParent()->getIdParameter()] = $admin->getRequest()->get($admin->getParent()->getIdParameter());
        }

                if ($admin->hasParentFieldDescription()) {
                        $parameters = array_merge($parameters, $admin->getParentFieldDescription()->getOption('link_parameters', array()));

            $parameters['uniqid']  = $admin->getUniqid();
            $parameters['code']    = $admin->getCode();
            $parameters['pcode']   = $admin->getParentFieldDescription()->getAdmin()->getCode();
            $parameters['puniqid'] = $admin->getParentFieldDescription()->getAdmin()->getUniqid();
        }

        if ($name == 'update' || substr($name, -7) == '|update') {
            $parameters['uniqid'] = $admin->getUniqid();
            $parameters['code']   = $admin->getCode();
        }

                if ($admin->hasRequest()) {
            $parameters = array_merge($admin->getPersistentParameters(), $parameters);
        }

        $route = $admin->getRoute($name);

        if (!$route) {
            throw new \RuntimeException(sprintf('unable to find the route `%s`', $name));
        }

        return $this->router->generate($route->getDefault('_sonata_name'), $parameters, $absolute);
    }
}
}
 

namespace Sonata\AdminBundle\Route
{

use Sonata\AdminBundle\Builder\RouteBuilderInterface;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Model\AuditManagerInterface;

class PathInfoBuilder implements RouteBuilderInterface
{
    protected $manager;

    
    public function __construct(AuditManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    
    public function build(AdminInterface $admin, RouteCollection $collection)
    {
        $collection->add('list');
        $collection->add('create');
        $collection->add('batch');
        $collection->add('edit', $admin->getRouterIdParameter().'/edit');
        $collection->add('delete', $admin->getRouterIdParameter().'/delete');
        $collection->add('show', $admin->getRouterIdParameter().'/show');
        $collection->add('export');

        if ($this->manager->hasReader($admin->getClass())) {
            $collection->add('history', $admin->getRouterIdParameter().'/history');
            $collection->add('history_view_revision', $admin->getRouterIdParameter().'/history/{revision}/view');
        }

                if ($admin->getParent()) {
            return;
        }

                foreach ($admin->getChildren() as $children) {
            $collection->addCollection($children->getRoutes());
        }
    }
}
}
 

namespace Sonata\AdminBundle\Route
{

use Sonata\AdminBundle\Builder\RouteBuilderInterface;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Model\AuditManagerInterface;

class QueryStringBuilder implements RouteBuilderInterface
{
    protected $manager;

    
    public function __construct(AuditManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    
    public function build(AdminInterface $admin, RouteCollection $collection)
    {
        $collection->add('list');
        $collection->add('create');
        $collection->add('batch');
        $collection->add('edit');
        $collection->add('delete');
        $collection->add('show');
        $collection->add('export');

        if ($this->manager->hasReader($admin->getClass())) {
            $collection->add('history', '/audit-history');
            $collection->add('history_view_revision', '/audit-history-view');
        }

                if ($admin->getParent()) {
            return;
        }

                foreach ($admin->getChildren() as $children) {
            $collection->addCollection($children->getRoutes());
        }
    }
}
}
 

namespace Sonata\AdminBundle\Route
{

use Symfony\Component\Routing\Route;

class RouteCollection
{
    protected $elements = array();

    protected $baseCodeRoute;

    protected $baseRouteName;

    protected $baseControllerName;

    protected $baseRoutePattern;

    
    public function __construct($baseCodeRoute, $baseRouteName, $baseRoutePattern, $baseControllerName)
    {
        $this->baseCodeRoute        = $baseCodeRoute;
        $this->baseRouteName        = $baseRouteName;
        $this->baseRoutePattern     = $baseRoutePattern;
        $this->baseControllerName   = $baseControllerName;
    }

    
    public function add($name, $pattern = null, array $defaults = array(), array $requirements = array(), array $options = array())
    {
        $pattern    = sprintf('%s/%s', $this->baseRoutePattern, $pattern ?: $name);
        $code       = $this->getCode($name);
        $routeName  = sprintf('%s_%s', $this->baseRouteName, $name);

        if (!isset($defaults['_controller'])) {
            $defaults['_controller'] = sprintf('%s:%s', $this->baseControllerName, $this->actionify($code));
        }

        if (!isset($defaults['_sonata_admin'])) {
            $defaults['_sonata_admin'] = $this->baseCodeRoute;
        }

        $defaults['_sonata_name'] = $routeName;

        $this->elements[$this->getCode($name)] = new Route($pattern, $defaults, $requirements, $options);
    }

    
    public function getCode($name)
    {
        if (strrpos($name, '.') !== false) {
            return $name;
        }

        return sprintf('%s.%s', $this->baseCodeRoute, $name);
    }

    
    public function addCollection(RouteCollection $collection)
    {
        foreach ($collection->getElements() as $code => $route) {
            $this->elements[$code] = $route;
        }
    }

    
    public function getElements()
    {
        return $this->elements;
    }

    
    public function has($name)
    {
        return array_key_exists($this->getCode($name), $this->elements);
    }

    
    public function get($name)
    {
        if ($this->has($name)) {
            return $this->elements[$this->getCode($name)];
        }

        throw new \InvalidArgumentException(sprintf('Element "%s" does not exist.', $name));
    }

    
    public function remove($name)
    {
        unset($this->elements[$this->getCode($name)]);

        return $this;
    }

    
    public function clearExcept(array $routeList)
    {
        $routeCodeList = array();
        foreach ($routeList as $name) {
            $routeCodeList[] = $this->getCode($name);
        }

        $elements = $this->elements;
        foreach ($elements as $key => $element) {
            if (!in_array($key, $routeCodeList)) {
                unset($this->elements[$key]);
            }
        }

        return $this;
    }

    
    public function clear()
    {
        $this->elements = array();

        return $this;
    }

    
    public function actionify($action)
    {
        if (($pos = strrpos($action, '.')) !== false) {

            $action = substr($action, $pos + 1);
        }

                        if (strpos($this->baseControllerName, ':') === false) {
            $action .= 'Action';
        }

        return lcfirst(str_replace(' ', '', ucwords(strtr($action, '_-', '  '))));
    }

    
    public function getBaseCodeRoute()
    {
        return $this->baseCodeRoute;
    }

    
    public function getBaseControllerName()
    {
        return $this->baseControllerName;
    }

    
    public function getBaseRouteName()
    {
        return $this->baseRouteName;
    }

    
    public function getBaseRoutePattern()
    {
        return $this->baseRoutePattern;
    }
}
}
 



namespace Symfony\Component\Security\Acl\Permission
{


interface PermissionMapInterface
{
    
    public function getMasks($permission, $object);

    
    public function contains($permission);
}
}
 



namespace Sonata\AdminBundle\Security\Acl\Permission
{

use Symfony\Component\Security\Acl\Permission\PermissionMapInterface;


class AdminPermissionMap implements PermissionMapInterface
{
    const PERMISSION_VIEW        = 'VIEW';
    const PERMISSION_EDIT        = 'EDIT';
    const PERMISSION_CREATE      = 'CREATE';
    const PERMISSION_DELETE      = 'DELETE';
    const PERMISSION_UNDELETE    = 'UNDELETE';
    const PERMISSION_LIST        = 'LIST';
    const PERMISSION_EXPORT      = 'EXPORT';
    const PERMISSION_OPERATOR    = 'OPERATOR';
    const PERMISSION_MASTER      = 'MASTER';
    const PERMISSION_OWNER       = 'OWNER';

    
    private $map = array(

        self::PERMISSION_VIEW => array(
            MaskBuilder::MASK_VIEW,
            MaskBuilder::MASK_LIST,
            MaskBuilder::MASK_EDIT,
            MaskBuilder::MASK_OPERATOR,
            MaskBuilder::MASK_MASTER,
            MaskBuilder::MASK_OWNER
        ),

        self::PERMISSION_EDIT => array(
            MaskBuilder::MASK_EDIT,
            MaskBuilder::MASK_OPERATOR,
            MaskBuilder::MASK_MASTER,
            MaskBuilder::MASK_OWNER
        ),

        self::PERMISSION_CREATE => array(
            MaskBuilder::MASK_CREATE,
            MaskBuilder::MASK_OPERATOR,
            MaskBuilder::MASK_MASTER,
            MaskBuilder::MASK_OWNER
        ),

        self::PERMISSION_DELETE => array(
            MaskBuilder::MASK_DELETE,
            MaskBuilder::MASK_OPERATOR,
            MaskBuilder::MASK_MASTER,
            MaskBuilder::MASK_OWNER
        ),

        self::PERMISSION_UNDELETE => array(
            MaskBuilder::MASK_UNDELETE,
            MaskBuilder::MASK_OPERATOR,
            MaskBuilder::MASK_MASTER,
            MaskBuilder::MASK_OWNER
        ),

        self::PERMISSION_LIST => array(
            MaskBuilder::MASK_LIST,
            MaskBuilder::MASK_OPERATOR,
            MaskBuilder::MASK_MASTER,
            MaskBuilder::MASK_OWNER
        ),

        self::PERMISSION_EXPORT => array(
            MaskBuilder::MASK_EXPORT,
            MaskBuilder::MASK_OPERATOR,
            MaskBuilder::MASK_MASTER,
            MaskBuilder::MASK_OWNER
        ),

        self::PERMISSION_OPERATOR => array(
            MaskBuilder::MASK_OPERATOR,
            MaskBuilder::MASK_MASTER,
            MaskBuilder::MASK_OWNER
        ),

        self::PERMISSION_MASTER => array(
            MaskBuilder::MASK_MASTER,
            MaskBuilder::MASK_OWNER,
        ),

        self::PERMISSION_OWNER => array(
            MaskBuilder::MASK_OWNER,
        ),
    );

    
    public function getMasks($permission, $object)
    {
        if (!isset($this->map[$permission])) {
            return null;
        }

        return $this->map[$permission];
    }

    
    public function contains($permission)
    {
        return isset($this->map[$permission]);
    }
}
}
 



namespace Symfony\Component\Security\Acl\Permission
{


class MaskBuilder
{
    const MASK_VIEW         = 1;              const MASK_CREATE       = 2;              const MASK_EDIT         = 4;              const MASK_DELETE       = 8;              const MASK_UNDELETE     = 16;             const MASK_OPERATOR     = 32;             const MASK_MASTER       = 64;             const MASK_OWNER        = 128;            const MASK_IDDQD        = 1073741823; 
    const CODE_VIEW         = 'V';
    const CODE_CREATE       = 'C';
    const CODE_EDIT         = 'E';
    const CODE_DELETE       = 'D';
    const CODE_UNDELETE     = 'U';
    const CODE_OPERATOR     = 'O';
    const CODE_MASTER       = 'M';
    const CODE_OWNER        = 'N';

    const ALL_OFF           = '................................';
    const OFF               = '.';
    const ON                = '*';

    private $mask;

    
    public function __construct($mask = 0)
    {
        if (!is_int($mask)) {
            throw new \InvalidArgumentException('$mask must be an integer.');
        }

        $this->mask = $mask;
    }

    
    public function add($mask)
    {
        if (is_string($mask) && defined($name = 'static::MASK_'.strtoupper($mask))) {
            $mask = constant($name);
        } elseif (!is_int($mask)) {
            throw new \InvalidArgumentException('$mask must be an integer.');
        }

        $this->mask |= $mask;

        return $this;
    }

    
    public function get()
    {
        return $this->mask;
    }

    
    public function getPattern()
    {
        $pattern = self::ALL_OFF;
        $length = strlen($pattern);
        $bitmask = str_pad(decbin($this->mask), $length, '0', STR_PAD_LEFT);

        for ($i=$length-1; $i>=0; $i--) {
            if ('1' === $bitmask[$i]) {
                try {
                    $pattern[$i] = self::getCode(1 << ($length - $i - 1));
                } catch (\Exception $notPredefined) {
                    $pattern[$i] = self::ON;
                }
            }
        }

        return $pattern;
    }

    
    public function remove($mask)
    {
        if (is_string($mask) && defined($name = 'static::MASK_'.strtoupper($mask))) {
            $mask = constant($name);
        } elseif (!is_int($mask)) {
            throw new \InvalidArgumentException('$mask must be an integer.');
        }

        $this->mask &= ~$mask;

        return $this;
    }

    
    public function reset()
    {
        $this->mask = 0;

        return $this;
    }

    
    public static function getCode($mask)
    {
        if (!is_int($mask)) {
            throw new \InvalidArgumentException('$mask must be an integer.');
        }

        $reflection = new \ReflectionClass(get_called_class());
        foreach ($reflection->getConstants() as $name => $cMask) {
            if (0 !== strpos($name, 'MASK_')) {
                continue;
            }

            if ($mask === $cMask) {
                if (!defined($cName = 'static::CODE_'.substr($name, 5))) {
                    throw new \RuntimeException('There was no code defined for this mask.');
                }

                return constant($cName);
            }
        }

        throw new \InvalidArgumentException(sprintf('The mask "%d" is not supported.', $mask));
    }
}
}
 


namespace Sonata\AdminBundle\Security\Acl\Permission
{

use Symfony\Component\Security\Acl\Permission\MaskBuilder as BaseMaskBuilder;


class MaskBuilder extends BaseMaskBuilder
{
    const MASK_LIST         = 4096;           const MASK_EXPORT       = 8192;       
    const CODE_LIST         = 'L';
    const CODE_EXPORT       = 'E';
}
}
 



namespace Sonata\AdminBundle\Security\Handler
{

use Sonata\AdminBundle\Admin\AdminInterface;

interface SecurityHandlerInterface
{
    
    public function isGranted(AdminInterface $admin, $attributes, $object = null);

    
    public function getBaseRole(AdminInterface $admin);

    
    public function buildSecurityInformation(AdminInterface $admin);

    
    public function createObjectSecurity(AdminInterface $admin, $object);

    
    public function deleteObjectSecurity(AdminInterface $admin, $object);
}
}
 



namespace Sonata\AdminBundle\Security\Handler
{

use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Model\AclInterface;
use Symfony\Component\Security\Acl\Model\ObjectIdentityInterface;

interface AclSecurityHandlerInterface extends SecurityHandlerInterface
{
    
    public function setAdminPermissions(array $permissions);

    
    public function getAdminPermissions();

    
    public function setObjectPermissions(array $permissions);

    
    public function getObjectPermissions();

    
    public function getObjectAcl(ObjectIdentityInterface $objectIdentity);

    
    public function findObjectAcls(array $oids, array $sids = array());

    
    public function addObjectOwner(AclInterface $acl, UserSecurityIdentity $securityIdentity = null);

    
    public function addObjectClassAces(AclInterface $acl, array $roleInformation = array());

    
    public function createAcl(ObjectIdentityInterface $objectIdentity);

    
    public function updateAcl(AclInterface $acl);

    
    public function deleteAcl(ObjectIdentityInterface $objectIdentity);

    
    public function findClassAceIndexByRole(AclInterface $acl, $role);

    
    public function findClassAceIndexByUsername(AclInterface $acl, $username);
}
}
 



namespace Sonata\AdminBundle\Security\Handler
{

use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
use Symfony\Component\Security\Acl\Model\MutableAclProviderInterface;
use Symfony\Component\Security\Acl\Model\AclInterface;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Model\ObjectIdentityInterface;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Domain\RoleSecurityIdentity;
use Symfony\Component\Security\Acl\Exception\AclNotFoundException;
use Symfony\Component\Security\Acl\Exception\NotAllAclsFoundException;
use Sonata\AdminBundle\Admin\AdminInterface;

class AclSecurityHandler implements AclSecurityHandlerInterface
{
    protected $securityContext;
    protected $aclProvider;
    protected $superAdminRoles;
    protected $adminPermissions;
    protected $objectPermissions;
    protected $maskBuilderClass;

    
    public function __construct(SecurityContextInterface $securityContext, MutableAclProviderInterface $aclProvider, $maskBuilderClass, array $superAdminRoles)
    {
        $this->securityContext  = $securityContext;
        $this->aclProvider      = $aclProvider;
        $this->maskBuilderClass = $maskBuilderClass;
        $this->superAdminRoles  = $superAdminRoles;
    }

    
    public function setAdminPermissions(array $permissions)
    {
        $this->adminPermissions = $permissions;
    }

    
    public function getAdminPermissions()
    {
        return $this->adminPermissions;
    }

    
    public function setObjectPermissions(array $permissions)
    {
        $this->objectPermissions = $permissions;
    }

    
    public function getObjectPermissions()
    {
        return $this->objectPermissions;
    }

    
    public function isGranted(AdminInterface $admin, $attributes, $object = null)
    {
        if (!is_array($attributes)) {
            $attributes = array($attributes);
        }

        try {
            return $this->securityContext->isGranted($this->superAdminRoles) || $this->securityContext->isGranted($attributes, $object);
        } catch (AuthenticationCredentialsNotFoundException $e) {
            return false;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    
    public function getBaseRole(AdminInterface $admin)
    {
        return 'ROLE_' . str_replace('.', '_', strtoupper($admin->getCode())) . '_%s';
    }

    
    public function buildSecurityInformation(AdminInterface $admin)
    {
        $baseRole = $this->getBaseRole($admin);

        $results = array();
        foreach ($admin->getSecurityInformation() as $role => $permissions) {
            $results[sprintf($baseRole, $role)] = $permissions;
        }

        return $results;
    }

    
    public function createObjectSecurity(AdminInterface $admin, $object)
    {
                $objectIdentity = ObjectIdentity::fromDomainObject($object);
        $acl            = $this->getObjectAcl($objectIdentity);
        if (is_null($acl)) {
            $acl = $this->createAcl($objectIdentity);
        }

                $user             = $this->securityContext->getToken()->getUser();
        $securityIdentity = UserSecurityIdentity::fromAccount($user);

        $this->addObjectOwner($acl, $securityIdentity);
        $this->addObjectClassAces($acl, $this->buildSecurityInformation($admin));
        $this->updateAcl($acl);
    }

    
    public function deleteObjectSecurity(AdminInterface $admin, $object)
    {
        $objectIdentity = ObjectIdentity::fromDomainObject($object);
        $this->deleteAcl($objectIdentity);
    }

    
    public function getObjectAcl(ObjectIdentityInterface $objectIdentity)
    {
        try {
            $acl = $this->aclProvider->findAcl($objectIdentity);
        } catch (AclNotFoundException $e) {
            return null;
        }

        return $acl;
    }

    
    public function findObjectAcls(array $oids, array $sids = array())
    {
        try {
            $acls = $this->aclProvider->findAcls($oids, $sids);
        } catch (\Exception $e) {
            if ($e instanceof NotAllAclsFoundException) {
                $acls = $e->getPartialResult();
            } elseif ($e instanceof AclNotFoundException) {
                                $acls = new \SplObjectStorage();
            } else {
                throw $e;
            }
        }

        return $acls;
    }

    
    public function addObjectOwner(AclInterface $acl, UserSecurityIdentity $securityIdentity = null)
    {
        if (false === $this->findClassAceIndexByUsername($acl, $securityIdentity->getUsername())) {
                        $acl->insertObjectAce($securityIdentity, constant("$this->maskBuilderClass::MASK_OWNER"));
        }
    }

    
    public function addObjectClassAces(AclInterface $acl, array $roleInformation = array())
    {
        $builder = new $this->maskBuilderClass();

        foreach ($roleInformation as $role => $permissions) {
            $aceIndex = $this->findClassAceIndexByRole($acl, $role);
            $hasRole  = false;

            foreach ($permissions as $permission) {
                                if (in_array($permission, $this->getObjectPermissions())) {
                    $builder->add($permission);
                    $hasRole = true;
                }
            }

            if ($hasRole) {
                if ($aceIndex === false) {
                    $acl->insertClassAce(new RoleSecurityIdentity($role), $builder->get());
                } else {
                    $acl->updateClassAce($aceIndex, $builder->get());
                }

                $builder->reset();
            } elseif ($aceIndex !== false) {
                $acl->deleteClassAce($aceIndex);
            }
        }
    }

    
    public function createAcl(ObjectIdentityInterface $objectIdentity)
    {
        return $this->aclProvider->createAcl($objectIdentity);
    }

    
    public function updateAcl(AclInterface $acl)
    {
        $this->aclProvider->updateAcl($acl);
    }

    
    public function deleteAcl(ObjectIdentityInterface $objectIdentity)
    {
        $this->aclProvider->deleteAcl($objectIdentity);
    }

    
    public function findClassAceIndexByRole(AclInterface $acl, $role)
    {
        foreach ($acl->getClassAces() as $index => $entry) {
            if ($entry->getSecurityIdentity() instanceof RoleSecurityIdentity && $entry->getSecurityIdentity()->getRole() === $role) {
                return $index;
            }
        }

        return false;
    }

    
    public function findClassAceIndexByUsername(AclInterface $acl, $username)
    {
        foreach ($acl->getClassAces() as $index => $entry) {
            if ($entry->getSecurityIdentity() instanceof UserSecurityIdentity && $entry->getSecurityIdentity()->getUsername() === $username) {
                return $index;
            }
        }

        return false;
    }
}
}
 



namespace Sonata\AdminBundle\Security\Handler
{

use Sonata\AdminBundle\Admin\AdminInterface;

class NoopSecurityHandler implements SecurityHandlerInterface
{
    
    public function isGranted(AdminInterface $admin, $attributes, $object = null)
    {
        return true;
    }

    
    public function getBaseRole(AdminInterface $admin)
    {
        return '';
    }

    
    public function buildSecurityInformation(AdminInterface $admin)
    {
        return array();
    }

    
    public function createObjectSecurity(AdminInterface $admin, $object)
    {
    }

    
    public function deleteObjectSecurity(AdminInterface $admin, $object)
    {
    }
}
}
 



namespace Sonata\AdminBundle\Security\Handler
{

use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
use Sonata\AdminBundle\Admin\AdminInterface;

class RoleSecurityHandler implements SecurityHandlerInterface
{
    protected $securityContext;

    protected $superAdminRoles;

    
    public function __construct(SecurityContextInterface $securityContext, array $superAdminRoles)
    {
        $this->securityContext = $securityContext;
        $this->superAdminRoles = $superAdminRoles;
    }

    
    public function isGranted(AdminInterface $admin, $attributes, $object = null)
    {
        if (!is_array($attributes)) {
            $attributes = array($attributes);
        }

        foreach ($attributes as $pos => $attribute) {
            $attributes[$pos] = sprintf($this->getBaseRole($admin), $attribute);
        }

        try {
            return $this->securityContext->isGranted($this->superAdminRoles) || $this->securityContext->isGranted($attributes);
        } catch (AuthenticationCredentialsNotFoundException $e) {
            return false;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    
    public function getBaseRole(AdminInterface $admin)
    {
        return 'ROLE_' . str_replace('.', '_', strtoupper($admin->getCode())) . '_%s';
    }

    
    public function buildSecurityInformation(AdminInterface $admin)
    {
        return array();
    }

    
    public function createObjectSecurity(AdminInterface $admin, $object)
    {
    }

    
    public function deleteObjectSecurity(AdminInterface $admin, $object)
    {
    }
}
}
 

namespace Sonata\AdminBundle\Show
{

use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Admin\FieldDescriptionInterface;
use Sonata\AdminBundle\Admin\FieldDescriptionCollection;
use Sonata\AdminBundle\Builder\ShowBuilderInterface;


class ShowMapper
{
    protected $showBuilder;

    protected $list;

    protected $admin;

    protected $currentGroup;

    
    public function __construct(ShowBuilderInterface $showBuilder, FieldDescriptionCollection $list, AdminInterface $admin)
    {
        $this->showBuilder = $showBuilder;
        $this->list        = $list;
        $this->admin       = $admin;
    }

    
    public function add($name, $type = null, array $fieldDescriptionOptions = array())
    {
        if (!$this->currentGroup) {
            $this->with($this->admin->getLabel());
        }

        $fieldKey = ($name instanceof FieldDescriptionInterface) ? $name->getName() : $name;

        $formGroups = $this->admin->getShowGroups();
        $formGroups[$this->currentGroup]['fields'][$fieldKey] = $fieldKey;
        $this->admin->setShowGroups($formGroups);

        if ($name instanceof FieldDescriptionInterface) {
            $fieldDescription = $name;
            $fieldDescription->mergeOptions($fieldDescriptionOptions);
        } elseif (is_string($name) && !$this->admin->hasShowFieldDescription($name)) {
            $fieldDescription = $this->admin->getModelManager()->getNewFieldDescriptionInstance(
                $this->admin->getClass(),
                $name,
                $fieldDescriptionOptions
            );
        } else {
            throw new \RuntimeException('invalid state');
        }

        if (!$fieldDescription->getLabel()) {
            $fieldDescription->setOption('label', $this->admin->getLabelTranslatorStrategy()->getLabel($fieldDescription->getName(), 'show', 'label'));
        }

        $fieldDescription->setOption('safe', $fieldDescription->getOption('safe', false));

                $this->showBuilder->addField($this->list, $type, $fieldDescription, $this->admin);

        return $this;
    }

    
    public function get($name)
    {
        return $this->list->get($name);
    }

    
    public function has($key)
    {
        return $this->list->has($key);
    }

    
    public function remove($key)
    {
        $this->admin->removeShowFieldDescription($key);
        $this->list->remove($key);

        return $this;
    }

    
    public function reorder(array $keys)
    {
        if (!$this->currentGroup) {
            $this->with($this->admin->getLabel());
        }

        $this->admin->reorderShowGroup($this->currentGroup, $keys);

        return $this;
    }

    
    public function with($name, array $options = array())
    {
        $showGroups = $this->admin->getShowGroups();
        if (!isset($showGroups[$name])) {
            $showGroups[$name] = array_merge(array(
                'collapsed' => false,
                'fields'    => array()
            ), $options);
        }

        $this->admin->setShowGroups($showGroups);

        $this->currentGroup = $name;

        return $this;
    }

    
    public function end()
    {
        $this->currentGroup = null;

        return $this;
    }
}
}
 



namespace Sonata\AdminBundle\Translator
{

interface LabelTranslatorStrategyInterface
{
    
    public function getLabel($label, $context = '', $type = '');
}
}
 



namespace Sonata\AdminBundle\Translator
{

class BCLabelTranslatorStrategy implements LabelTranslatorStrategyInterface
{
    
    public function getLabel($label, $context = '', $type = '')
    {
        if ($context == 'breadcrumb') {
            return sprintf('%s.%s_%s', $context, $type, strtolower($label));
        }

        return ucfirst(strtolower($label));
    }
}
}
 



namespace Sonata\AdminBundle\Translator
{

class FormLabelTranslatorStrategy implements LabelTranslatorStrategyInterface
{
    
    public function getLabel($label, $context = '', $type = '')
    {
        return ucfirst(strtolower($label));
    }
}
}
 



namespace Sonata\AdminBundle\Translator
{

class NativeLabelTranslatorStrategy implements LabelTranslatorStrategyInterface
{
    
    public function getLabel($label, $context = '', $type = '')
    {
        $label = str_replace(array('_', '.'), ' ', $label);
        $label = strtolower(preg_replace('~(?<=\\w)([A-Z])~', '_$1', $label));

        return ucwords(str_replace('_', ' ', $label));
    }
}
}
 



namespace Sonata\AdminBundle\Translator
{

class NoopLabelTranslatorStrategy implements LabelTranslatorStrategyInterface
{
    
    public function getLabel($label, $context = '', $type = '')
    {
        return $label;
    }
}
}
 



namespace Sonata\AdminBundle\Translator
{

class UnderscoreLabelTranslatorStrategy implements LabelTranslatorStrategyInterface
{
    
    public function getLabel($label, $context = '', $type = '')
    {
        $label = str_replace('.', '_', $label);

        return sprintf('%s.%s_%s', $context, $type, strtolower(preg_replace('~(?<=\\w)([A-Z])~', '_$1', $label)));
    }
}
}

namespace
{

/*
 * This file is part of Twig.
 *
 * (c) 2009 Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
/**
 * Interface implemented by extension classes.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
interface Twig_ExtensionInterface
{
    /**
     * Initializes the runtime environment.
     *
     * This is where you can load some file that contains filter functions for instance.
     *
     * @param Twig_Environment $environment The current Twig_Environment instance
     */
    public function initRuntime(Twig_Environment $environment);
    /**
     * Returns the token parser instances to add to the existing list.
     *
     * @return array An array of Twig_TokenParserInterface or Twig_TokenParserBrokerInterface instances
     */
    public function getTokenParsers();
    /**
     * Returns the node visitor instances to add to the existing list.
     *
     * @return array An array of Twig_NodeVisitorInterface instances
     */
    public function getNodeVisitors();
    /**
     * Returns a list of filters to add to the existing list.
     *
     * @return array An array of filters
     */
    public function getFilters();
    /**
     * Returns a list of tests to add to the existing list.
     *
     * @return array An array of tests
     */
    public function getTests();
    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions();
    /**
     * Returns a list of operators to add to the existing list.
     *
     * @return array An array of operators
     */
    public function getOperators();
    /**
     * Returns a list of global variables to add to the existing list.
     *
     * @return array An array of global variables
     */
    public function getGlobals();
    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName();
}

}

namespace
{

/*
 * This file is part of Twig.
 *
 * (c) 2009 Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
abstract class Twig_Extension implements Twig_ExtensionInterface
{
    /**
     * Initializes the runtime environment.
     *
     * This is where you can load some file that contains filter functions for instance.
     *
     * @param Twig_Environment $environment The current Twig_Environment instance
     */
    public function initRuntime(Twig_Environment $environment)
    {
    }
    /**
     * Returns the token parser instances to add to the existing list.
     *
     * @return array An array of Twig_TokenParserInterface or Twig_TokenParserBrokerInterface instances
     */
    public function getTokenParsers()
    {
        return array();
    }
    /**
     * Returns the node visitor instances to add to the existing list.
     *
     * @return array An array of Twig_NodeVisitorInterface instances
     */
    public function getNodeVisitors()
    {
        return array();
    }
    /**
     * Returns a list of filters to add to the existing list.
     *
     * @return array An array of filters
     */
    public function getFilters()
    {
        return array();
    }
    /**
     * Returns a list of tests to add to the existing list.
     *
     * @return array An array of tests
     */
    public function getTests()
    {
        return array();
    }
    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return array();
    }
    /**
     * Returns a list of operators to add to the existing list.
     *
     * @return array An array of operators
     */
    public function getOperators()
    {
        return array();
    }
    /**
     * Returns a list of global variables to add to the existing list.
     *
     * @return array An array of global variables
     */
    public function getGlobals()
    {
        return array();
    }
}

}
 



namespace Sonata\AdminBundle\Twig\Extension
{

use Doctrine\Common\Util\ClassUtils;
use Sonata\AdminBundle\Admin\FieldDescriptionInterface;
use Sonata\AdminBundle\Exception\NoValueException;
use Sonata\AdminBundle\Admin\Pool;

class SonataAdminExtension extends \Twig_Extension
{
    
    protected $environment;

    
    protected $pool;

    
    public function __construct(Pool $pool)
    {
        $this->pool = $pool;
    }

    
    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    
    public function getFilters()
    {
        return array(
            'render_list_element'     => new \Twig_Filter_Method($this, 'renderListElement', array('is_safe' => array('html'))),
            'render_view_element'     => new \Twig_Filter_Method($this, 'renderViewElement', array('is_safe' => array('html'))),
            'render_relation_element' => new \Twig_Filter_Method($this, 'renderRelationElement'),
            'sonata_urlsafeid'        => new \Twig_Filter_Method($this, 'getUrlsafeIdentifier'),
        );
    }

    
    public function getTokenParsers()
    {
        return array();
    }

    
    public function getName()
    {
        return 'sonata_admin';
    }

    
    protected function getTemplate(FieldDescriptionInterface $fieldDescription, $default)
    {
                $templateName = $fieldDescription->getTemplate() ? : $default;

        try {
            $template = $this->environment->loadTemplate($templateName);
        } catch (\Twig_Error_Loader $e) {
            $template = $this->environment->loadTemplate($default);
        }

        return $template;
    }

    
    public function renderListElement($object, FieldDescriptionInterface $fieldDescription, $params = array())
    {
        $template = $this->getTemplate($fieldDescription, 'SonataAdminBundle:CRUD:base_list_field.html.twig');

        return $this->output($fieldDescription, $template, array_merge($params, array(
            'admin'             => $fieldDescription->getAdmin(),
            'object'            => $object,
            'value'             => $this->getValueFromFieldDescription($object, $fieldDescription),
            'field_description' => $fieldDescription
        )));
    }

    
    public function output(FieldDescriptionInterface $fieldDescription, \Twig_TemplateInterface $template, array $parameters = array())
    {
        $content = $template->render($parameters);

        if ($this->environment->isDebug()) {
            return sprintf("\n<!-- START  \n  fieldName: %s\n  template: %s\n  compiled template: %s\n -->\n%s\n<!-- END - fieldName: %s -->",
                $fieldDescription->getFieldName(),
                $fieldDescription->getTemplate(),
                $template->getTemplateName(),
                $content,
                $fieldDescription->getFieldName()
            );
        }

        return $content;
    }

    
    public function getValueFromFieldDescription($object, FieldDescriptionInterface $fieldDescription, array $params = array())
    {
        if (isset($params['loop']) && $object instanceof \ArrayAccess) {
            throw new \RuntimeException('remove the loop requirement');
        }

        $value = null;
        try {
            $value = $fieldDescription->getValue($object);
        } catch (NoValueException $e) {
            if ($fieldDescription->getAssociationAdmin()) {
                $value = $fieldDescription->getAssociationAdmin()->getNewInstance();
            }
        }

        return $value;
    }

    
    public function renderViewElement(FieldDescriptionInterface $fieldDescription, $object)
    {
        $template = $this->getTemplate($fieldDescription, 'SonataAdminBundle:CRUD:base_show_field.html.twig');

        try {
            $value = $fieldDescription->getValue($object);
        } catch (NoValueException $e) {
            $value = null;
        }

        return $this->output($fieldDescription, $template, array(
            'field_description' => $fieldDescription,
            'object'            => $object,
            'value'             => $value,
            'admin'             => $fieldDescription->getAdmin()
        ));
    }

    
    public function renderRelationElement($element, FieldDescriptionInterface $fieldDescription)
    {
        $method = $fieldDescription->getOption('associated_tostring', '__toString');

        if (!is_object($element)) {
            return $element;
        }

        if (!method_exists($element, $method)) {
            throw new \RunTimeException(sprintf('You must define an `associated_tostring` option or create a `%s::__toString` method to the field option %s from service %s is ', get_class($element), $fieldDescription->getName(), $fieldDescription->getAdmin()->getCode()));
        }

        return call_user_func(array($element, $method));
    }

    
    public function getUrlsafeIdentifier($model)
    {
        $admin = $this->pool->getAdminByClass(
            ClassUtils::getClass($model)
        );

        return $admin->getUrlsafeIdentifier($model);
    }
}
}
 



namespace Sonata\AdminBundle\Util
{

use Symfony\Component\Security\Acl\Model\AclInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Security\Handler\AclSecurityHandlerInterface;

interface AdminAclManipulatorInterface
{
    
    public function configureAcls(OutputInterface $output, AdminInterface $admin);

    
    public function addAdminClassAces(OutputInterface $output, AclInterface $acl, AclSecurityHandlerInterface $securityHandler, array $roleInformation = array());
}
}
 



namespace Sonata\AdminBundle\Util
{

use Symfony\Component\Security\Acl\Domain\RoleSecurityIdentity;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Model\AclInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Security\Handler\AclSecurityHandlerInterface;

class AdminAclManipulator implements AdminAclManipulatorInterface
{
    protected $maskBuilderClass;

    
    public function __construct($maskBuilderClass)
    {
        $this->maskBuilderClass = $maskBuilderClass;
    }

    
    public function configureAcls(OutputInterface $output, AdminInterface $admin)
    {
        $securityHandler = $admin->getSecurityHandler();
        if (!$securityHandler instanceof AclSecurityHandlerInterface) {
            $output->writeln(sprintf('Admin `%s` is not configured to use ACL : <info>ignoring</info>', $admin->getCode()));

            return;
        }

        $objectIdentity = ObjectIdentity::fromDomainObject($admin);
        $newAcl = false;
        if (is_null($acl = $securityHandler->getObjectAcl($objectIdentity))) {
            $acl = $securityHandler->createAcl($objectIdentity);
            $newAcl = true;
        }

                $output->writeln(sprintf(' > install ACL for %s', $admin->getCode()));
        $configResult = $this->addAdminClassAces($output, $acl, $securityHandler, $securityHandler->buildSecurityInformation($admin));

        if ($configResult) {
            $securityHandler->updateAcl($acl);
        } else {
            $output->writeln(sprintf('   - %s , no roles and permissions found', ($newAcl ? 'skip' : 'removed')));
            $securityHandler->deleteAcl($objectIdentity);
        }
    }

    
    public function addAdminClassAces(OutputInterface $output, AclInterface $acl, AclSecurityHandlerInterface $securityHandler, array $roleInformation = array())
    {
        if (count($securityHandler->getAdminPermissions()) > 0 ) {
            $builder = new $this->maskBuilderClass();

            foreach ($roleInformation as $role => $permissions) {
                $aceIndex = $securityHandler->findClassAceIndexByRole($acl, $role);
                $roleAdminPermissions = array();

                foreach ($permissions as $permission) {
                                        if (in_array($permission, $securityHandler->getAdminPermissions())) {
                        $builder->add($permission);
                        $roleAdminPermissions[] = $permission;
                    }
                }

                if (count($roleAdminPermissions) > 0) {
                    if ($aceIndex === false) {
                        $acl->insertClassAce(new RoleSecurityIdentity($role), $builder->get());
                        $action = 'add';
                    } else {
                        $acl->updateClassAce($aceIndex, $builder->get());
                        $action = 'update';
                    }

                    if (!is_null($output)) {
                        $output->writeln(sprintf('   - %s role: %s, permissions: %s', $action, $role, json_encode($roleAdminPermissions)));
                    }

                    $builder->reset();
                } elseif ($aceIndex !== false) {
                    $acl->deleteClassAce($aceIndex);

                    if (!is_null($output)) {
                        $output->writeln(sprintf('   - remove role: %s', $action, $role));
                    }
                }
            }

            return true;
        } else {
            return false;
        }
    }
}
}
 


namespace Sonata\AdminBundle\Util
{

use Symfony\Component\Form\FormBuilder;

class FormBuilderIterator extends \RecursiveArrayIterator
{
    protected static $reflection;

    protected $formBuilder;

    protected $keys = array();

    protected $prefix;

    
    public function __construct(FormBuilder $formBuilder, $prefix = false)
    {
        $this->formBuilder = $formBuilder;
        $this->prefix      = $prefix ? $prefix : $formBuilder->getName();
        $this->iterator    = new \ArrayIterator(self::getKeys($formBuilder));
    }

    
    private static function getKeys(FormBuilder $formBuilder)
    {
        if (!self::$reflection) {
            self::$reflection = new \ReflectionProperty(get_class($formBuilder), 'children');
            self::$reflection->setAccessible(true);
        }

        return array_keys(self::$reflection->getValue($formBuilder));
    }

    
    public function rewind()
    {
        $this->iterator->rewind();
    }

    
    public function valid()
    {
        return $this->iterator->valid();
    }

    
    public function key()
    {
        $name = $this->iterator->current();

        return sprintf('%s_%s', $this->prefix, $name);
    }

    
    public function next()
    {
        $this->iterator->next();
    }

    
    public function current()
    {
        return $this->formBuilder->get($this->iterator->current());
    }

    
    public function getChildren()
    {
        return new self($this->formBuilder->get($this->iterator->current()), $this->current());
    }

    
    public function hasChildren()
    {
        return count(self::getKeys($this->current())) > 0;
    }
}
}
 


namespace Sonata\AdminBundle\Util
{

use Symfony\Component\Form\FormView;

class FormViewIterator implements \RecursiveIterator
{
    protected $formView;

    
    public function __construct(FormView $formView)
    {
        $this->iterator = $formView->getIterator();
    }

    
    public function getChildren()
    {
        return new FormViewIterator($this->current());
    }

    
    public function hasChildren()
    {
        return count($this->current()->children) > 0;
    }

    
    public function current()
    {
        return $this->iterator->current();
    }

    
    public function next()
    {
        $this->iterator->next();
    }

    
    public function key()
    {
        return $this->current()->vars['id'];
    }

    
    public function valid()
    {
        return $this->iterator->valid();
    }

    
    public function rewind()
    {
        $this->iterator->rewind();
    }
}
}
 



namespace Sonata\AdminBundle\Util
{

use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Console\Output\OutputInterface;
use Sonata\AdminBundle\Admin\AdminInterface;

interface ObjectAclManipulatorInterface
{
    
    public function batchConfigureAcls(OutputInterface $output, AdminInterface $admin, UserSecurityIdentity $securityIdentity = null);
}
}
 



namespace Sonata\AdminBundle\Util
{

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Security\Handler\AclSecurityHandlerInterface;

abstract class ObjectAclManipulator implements ObjectAclManipulatorInterface
{
    
    public function configureAcls(OutputInterface $output, AdminInterface $admin, array $oids, UserSecurityIdentity $securityIdentity = null)
    {
        $countAdded      = 0;
        $countUpdated    = 0;
        $securityHandler = $admin->getSecurityHandler();
        if (!$securityHandler instanceof AclSecurityHandlerInterface) {
            $output->writeln(sprintf('Admin `%s` is not configured to use ACL : <info>ignoring</info>', $admin->getCode()));

            return array(0, 0);
        }

        $acls = $securityHandler->findObjectAcls($oids);

        foreach ($oids as $oid) {
            if ($acls->contains($oid)) {
                $acl = $acls->offsetGet($oid);
                $countUpdated++;
            } else {
                $acl = $securityHandler->createAcl($oid);
                $countAdded++;
            }

            if (!is_null($securityIdentity)) {
                                $securityHandler->addObjectOwner($acl, $securityIdentity);
            }

            $securityHandler->addObjectClassAces($acl, $securityHandler->buildSecurityInformation($admin));

            try {
                $securityHandler->updateAcl($acl);
            } catch (\Exception $e) {
                $output->writeln(sprintf('Error saving ObjectIdentity (%s, %s) ACL : %s <info>ignoring</info>', $oid->getIdentifier(), $oid->getType(), $e->getMessage()));
            }
        }

        return array($countAdded, $countUpdated);
    }
}
}
 



namespace Symfony\Component\Validator
{

use Symfony\Component\Validator\Exception\InvalidOptionsException;
use Symfony\Component\Validator\Exception\MissingOptionsException;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;


abstract class Constraint
{
    
    const DEFAULT_GROUP = 'Default';

    
    const CLASS_CONSTRAINT = 'class';

    
    const PROPERTY_CONSTRAINT = 'property';

    
    public $groups = array(self::DEFAULT_GROUP);

    
    public function __construct($options = null)
    {
        $invalidOptions = array();
        $missingOptions = array_flip((array) $this->getRequiredOptions());

        if (is_array($options) && count($options) == 1 && isset($options['value'])) {
            $options = $options['value'];
        }

        if (is_array($options) && count($options) > 0 && is_string(key($options))) {
            foreach ($options as $option => $value) {
                if (property_exists($this, $option)) {
                    $this->$option = $value;
                    unset($missingOptions[$option]);
                } else {
                    $invalidOptions[] = $option;
                }
            }
        } elseif (null !== $options && ! (is_array($options) && count($options) === 0)) {
            $option = $this->getDefaultOption();

            if (null === $option) {
                throw new ConstraintDefinitionException(
                    sprintf('No default option is configured for constraint %s', get_class($this))
                );
            }

            if (property_exists($this, $option)) {
                $this->$option = $options;
                unset($missingOptions[$option]);
            } else {
                $invalidOptions[] = $option;
            }
        }

        if (count($invalidOptions) > 0) {
            throw new InvalidOptionsException(
                sprintf('The options "%s" do not exist in constraint %s', implode('", "', $invalidOptions), get_class($this)),
                $invalidOptions
            );
        }

        if (count($missingOptions) > 0) {
            throw new MissingOptionsException(
                sprintf('The options "%s" must be set for constraint %s', implode('", "', array_keys($missingOptions)), get_class($this)),
                array_keys($missingOptions)
            );
        }

        $this->groups = (array) $this->groups;
    }

    
    public function __set($option, $value)
    {
        throw new InvalidOptionsException(sprintf('The option "%s" does not exist in constraint %s', $option, get_class($this)), array($option));
    }

    
    public function addImplicitGroupName($group)
    {
        if (in_array(Constraint::DEFAULT_GROUP, $this->groups) && !in_array($group, $this->groups)) {
            $this->groups[] = $group;
        }
    }

    
    public function getDefaultOption()
    {
        return null;
    }

    
    public function getRequiredOptions()
    {
        return array();
    }

    
    public function validatedBy()
    {
        return get_class($this).'Validator';
    }

    
    public function getTargets()
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
}
 


namespace Sonata\AdminBundle\Validator\Constraints
{

use Symfony\Component\Validator\Constraint;

class InlineConstraint extends Constraint
{
    protected $service;

    protected $method;

    
    public function validatedBy()
    {
        return 'sonata.admin.validator.inline';
    }

    
    public function isClosure()
    {
        return $this->method instanceof \Closure;
    }

    
    public function getClosure()
    {
        return $this->method;
    }

    
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    
    public function getRequiredOptions()
    {
        return array(
            'service',
            'method'
        );
    }

    
    public function getMethod()
    {
        return $this->method;
    }

    
    public function getService()
    {
        return $this->service;
    }
}
}
 


namespace Sonata\AdminBundle\Validator
{

use Symfony\Bundle\FrameworkBundle\Validator\ConstraintValidatorFactory;
use Symfony\Component\Validator\ExecutionContext;
use Symfony\Component\Form\Util\PropertyPath;
use Symfony\Component\Validator\Constraint;

class ErrorElement
{
    protected $context;

    protected $group;

    protected $constraintValidatorFactory;

    protected $stack = array();

    protected $propertyPaths = array();

    protected $subject;

    protected $current;

    protected $basePropertyPath;

    protected $errors = array();

    
    public function __construct($subject, ConstraintValidatorFactory $constraintValidatorFactory, ExecutionContext $context, $group)
    {
        $this->subject                    = $subject;
        $this->context                    = $context;
        $this->group                      = $group;
        $this->constraintValidatorFactory = $constraintValidatorFactory;

        $this->current          = '';
        $this->basePropertyPath = $this->context->getPropertyPath();
    }

    
    public function __call($name, array $arguments = array())
    {
        if (substr($name, 0, 6) == 'assert') {
            $this->validate($this->newConstraint(substr($name, 6), isset($arguments[0]) ? $arguments[0] : array()));
        } else {
            throw new \RunTimeException('Unable to recognize the command');
        }

        return $this;
    }

    
    public function addConstraint(Constraint $constraint)
    {
        $this->validate($constraint);

        return $this;
    }

    
    public function with($name, $key = false)
    {
        $key           = $key ? $name . '.' . $key : $name;
        $this->stack[] = $key;

        $this->current = implode('.', $this->stack);

        if (!isset($this->propertyPaths[$this->current])) {
            $this->propertyPaths[$this->current] = new PropertyPath($this->current);
        }

        return $this;
    }

    
    public function end()
    {
        array_pop($this->stack);

        $this->current = implode('.', $this->stack);

        return $this;
    }

    
    protected function validate(Constraint $constraint)
    {
        $validator = $this->constraintValidatorFactory->getInstance($constraint);
        $value     = $this->getValue();

        $validator->initialize($this->context);
        $validator->validate($value, $constraint);
    }

    
    public function getFullPropertyPath()
    {
        if ($this->getCurrentPropertyPath()) {
            return sprintf('%s.%s', $this->basePropertyPath, $this->getCurrentPropertyPath());
        } else {
            return $this->basePropertyPath;
        }
    }

    
    protected function getValue()
    {
        return $this->getCurrentPropertyPath()->getValue($this->subject);
    }

    
    public function getSubject()
    {
        return $this->subject;
    }

    
    protected function newConstraint($name, array $options = array())
    {
        if (strpos($name, '\\') !== false && class_exists($name)) {
            $className = (string) $name;
        } else {
            $className = 'Symfony\\Component\\Validator\\Constraints\\' . $name;
        }

        return new $className($options);
    }

    
    protected function getCurrentPropertyPath()
    {
        if (!isset($this->propertyPaths[$this->current])) {
            return null;         }

        return $this->propertyPaths[$this->current];
    }

    
    public function addViolation($message, $parameters = array(), $value = null)
    {
        if (is_array($message)) {
            $value      = isset($message[2]) ? $message[2] : $value;
            $parameters = isset($message[1]) ? (array) $message[1] : array();
            $message    = isset($message[0]) ? $message[0] : 'error';
        }

        $this->context->addViolationAtPath($this->getFullPropertyPath(), $message, $parameters, $value);

        $this->errors[] = array($message, $parameters, $value);

        return $this;
    }

    
    public function getErrors()
    {
        return $this->errors;
    }
}
}
 



namespace Symfony\Component\Validator
{


interface ConstraintValidatorInterface
{
    
    public function initialize(ExecutionContext $context);

    
    public function validate($value, Constraint $constraint);
}
}
 



namespace Symfony\Component\Validator
{

use Symfony\Component\Validator\Exception\ValidatorException;


abstract class ConstraintValidator implements ConstraintValidatorInterface
{
    
    protected $context;

    
    private $messageTemplate;

    
    private $messageParameters;

    
    public function initialize(ExecutionContext $context)
    {
        $this->context = $context;
        $this->messageTemplate = '';
        $this->messageParameters = array();
    }

    
    public function getMessageTemplate()
    {
        return $this->messageTemplate;
    }

    
    public function getMessageParameters()
    {
        return $this->messageParameters;
    }

    
    protected function setMessage($template, array $parameters = array())
    {
        $this->messageTemplate = $template;
        $this->messageParameters = $parameters;

        if (!$this->context instanceof ExecutionContext) {
            throw new ValidatorException('ConstraintValidator::initialize() must be called before setting violation messages');
        }

        $this->context->addViolation($template, $parameters);
    }

    
    public function validate($value, Constraint $constraint)
    {
        return $this->isValid($value, $constraint);
    }

    
    protected function isValid($value, Constraint $constraint)
    {
    }
}
}
 


namespace Sonata\AdminBundle\Validator
{

use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Validator\ConstraintValidatorFactory;
use Sonata\AdminBundle\Validator\ErrorElement;

class InlineValidator extends ConstraintValidator
{
    protected $container;

    
    public function __construct(ContainerInterface $container, ConstraintValidatorFactory $constraintValidatorFactory)
    {
        $this->container                  = $container;
        $this->constraintValidatorFactory = $constraintValidatorFactory;
    }

    
    public function isValid($value, Constraint $constraint)
    {
        $errorElement = new ErrorElement(
            $value,
            $this->constraintValidatorFactory,
            $this->context,
            $this->context->getGroup()
        );

        if ($constraint->isClosure()) {
            $function = $constraint->getClosure();
        } else {
            if (is_string($constraint->getService())) {
                $service = $this->container->get($constraint->getService());
            } else {
                $service = $constraint->getService();
            }

            $function = array($service, $constraint->getMethod());
        }

        call_user_func($function, $errorElement, $value);

        return true;
    }
}
}
 



namespace Sonata\BlockBundle\Block
{

interface BlockLoaderInterface
{
    
    public function load($name);

    
    public function support($name);
}
}
 



namespace Sonata\BlockBundle\Block
{

use Sonata\BlockBundle\Exception\BlockNotFoundException;

class BlockLoaderChain implements BlockLoaderInterface
{
    protected $loaders;

    
    public function __construct(array $loaders)
    {
        $this->loaders = $loaders;
    }

    
    public function load($block)
    {
        foreach ($this->loaders as $loader) {
            if ($loader->support($block)) {
                return $loader->load($block);
            }
        }

        throw new BlockNotFoundException;
    }

    
    public function support($name)
    {
        return true;
    }
}
}
 



namespace Sonata\BlockBundle\Block
{

use Sonata\BlockBundle\Model\BlockInterface;
use Symfony\Component\HttpFoundation\Response;

interface BlockRendererInterface
{
    
    public function render(BlockInterface $name, Response $response = null);
}
}
 



namespace Sonata\BlockBundle\Block
{

use Sonata\BlockBundle\Model\BlockInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Log\LoggerInterface;

use Sonata\BlockBundle\Exception\Strategy\StrategyManagerInterface;


class BlockRenderer implements BlockRendererInterface
{
    
    protected $blockServiceManager;

    
    protected $exceptionStrategyManager;

    
    protected $logger;

    
    protected $debug;

    
    public function __construct(BlockServiceManagerInterface $blockServiceManager, StrategyManagerInterface $exceptionStrategyManager, LoggerInterface $logger = null, $debug = false)
    {
        $this->blockServiceManager      = $blockServiceManager;
        $this->exceptionStrategyManager = $exceptionStrategyManager;
        $this->logger                   = $logger;
        $this->debug                    = $debug;
    }

    
    public function render(BlockInterface $block, Response $response = null)
    {
        if ($this->logger) {
            $this->logger->info(sprintf('[cms::renderBlock] block.id=%d, block.type=%s ', $block->getId(), $block->getType()));
        }

        try {
            $service = $this->blockServiceManager->get($block);
            $service->load($block);

            if (null === $response) {
                                $defaultSettings = $service->getDefaultSettings();
                $block->setSetting('ttl', isset($defaultSettings['ttl']) ? $defaultSettings['ttl'] : $block->getTtl());

                $response = new Response();
                $response->setTtl($block->getTtl());
            }

            $newResponse = $service->execute($block, $response);

            if (!$newResponse instanceof Response) {
                throw new \RuntimeException('A block service must return a Response object');
            }

        } catch (\Exception $exception) {
            if ($this->logger) {
                $this->logger->crit(sprintf('[cms::renderBlock] block.id=%d - error while rendering block - %s', $block->getId(), $exception->getMessage()));
            }
            $newResponse = $this->exceptionStrategyManager->handleException($exception, $block, $response);
        }

        return $newResponse;
    }
}
}
 


namespace Sonata\BlockBundle\Block
{

use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\BlockBundle\Model\BlockInterface;

interface BlockServiceManagerInterface
{
    
    public function add($name, $service);

    
    public function get(BlockInterface $block);

    
    public function setServices(array $blockServices);

    
    public function getServices();

    
    public function has($name);

    
    public function getService($name);

    
    public function getLoadedServices();

    
    public function validate(ErrorElement $errorElement, BlockInterface $block);
}
}
 



namespace Sonata\BlockBundle\Block
{

use Sonata\BlockBundle\Model\BlockInterface;

use Sonata\AdminBundle\Validator\ErrorElement;

use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class BlockServiceManager implements BlockServiceManagerInterface
{
    
    protected $services;

    
    protected $container;

    protected $inValidate;

    
    public function __construct(ContainerInterface $container, $debug, LoggerInterface $logger = null)
    {
        $this->services  = array();
        $this->container = $container;
    }

    
    private function load($type)
    {
        if (!$this->has($type)) {
            throw new \RuntimeException(sprintf('The block service `%s` does not exists', $type));
        }

        if (!$this->services[$type] instanceof BlockServiceInterface) {
            $this->services[$type] = $this->container->get($type);
        }

        if (!$this->services[$type] instanceof BlockServiceInterface) {
            throw new \RuntimeException(sprintf('The service %s does not implement BlockServiceInterface', $type));
        }

        return $this->services[$type];
    }

    
    public function get(BlockInterface $block)
    {
        $this->load($block->getType());

        return $this->services[$block->getType()];
    }

    
    public function getService($id)
    {
        return $this->load($id);
    }

    
    public function has($id)
    {
        return isset($this->services[$id]) ? true : false;
    }

    
    public function add($name, $service)
    {
        $this->services[$name] = $service;
    }

    
    public function setServices(array $blockServices)
    {
        $this->services = $blockServices;
    }

    
    public function getServices()
    {
        foreach ($this->services as $name => $id) {
            if (is_string($id)) {
                $this->load($id);
            }
        }

        return $this->services;
    }

    
    public function getLoadedServices()
    {
        $services = array();

        foreach ($this->services as $service) {
            if (!$service instanceof BlockServiceInterface) {
                continue;
            }

            $services[] = $service;
        }

        return $services;
    }

    
    public function validate(ErrorElement $errorElement, BlockInterface $block)
    {
        if (!$block->getId() && !$block->getType()) {
            return;
        }

        if ($this->inValidate) {
            return;
        }

                try {
            $this->inValidate = true;
            $this->get($block)->validateBlock($errorElement, $block);
            $this->inValidate = false;
        } catch (\Exception $e) {
            $this->inValidate = false;
        }
    }
}
}
 



namespace Sonata\BlockBundle\Block\Loader
{

use Sonata\BlockBundle\Block\BlockLoaderInterface;
use Sonata\BlockBundle\Model\Block;

class ServiceLoader implements BlockLoaderInterface
{
    protected $settings;

    
    public function __construct(array $settings)
    {
        $this->settings     = $settings;
    }

    
    public function load($configuration)
    {
        $block = new Block;
        $block->setId(uniqid());
        $block->setType($configuration['type']);
        $block->setSettings($this->getSettings($configuration));
        $block->setEnabled(true);
        $block->setCreatedAt(new \DateTime);
        $block->setUpdatedAt(new \DateTime);

        return $block;
    }

    
    public function support($configuration)
    {
        if (!is_array($configuration)) {
            return false;
        }

        if (!isset($configuration['type'])) {
            return false;
        }

        return true;
    }

    
    private function getSettings($block)
    {
        if (!isset($this->settings[$block['type']])) {
            throw new \RuntimeException(sprintf('The block type %s does not exist', $block['type']));
        }

        return array_merge(
            $this->settings[$block['type']],
            isset($block['settings']) && is_array($block['settings']) ? $block['settings'] : array()
        );
    }
}
}
 



namespace Sonata\BlockBundle\Block\Service
{

use Symfony\Component\HttpFoundation\Response;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\BlockBundle\Block\BaseBlockService;

class EmptyBlockService extends BaseBlockService
{
    
    public function buildEditForm(FormMapper $form, BlockInterface $block)
    {
        throw new \RuntimeException('Not used, this block renders an empty result if no block document can be found');
    }

    
    public function validateBlock(ErrorElement $errorElement, BlockInterface $block)
    {
        throw new \RuntimeException('Not used, this block renders an empty result if no block document can be found');
    }

    
    public function execute(BlockInterface $block, Response $response = null)
    {
        return new Response();
    }
}
}
 



namespace Sonata\BlockBundle\Block\Service
{

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Form;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Validator\ErrorElement;

use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\BlockBundle\Block\BaseBlockService;


class RssBlockService extends BaseBlockService
{
    
    public function getName()
    {
        return 'Rss Reader';
    }

    
    public function getDefaultSettings()
    {
        return array(
            'url'     => false,
            'title'   => 'Insert the rss title'
        );
    }

    
    public function buildEditForm(FormMapper $formMapper, BlockInterface $block)
    {
        $formMapper->add('settings', 'sonata_type_immutable_array', array(
            'keys' => array(
                array('url', 'url', array('required' => false)),
                array('title', 'text', array('required' => false)),
            )
        ));
    }

    
    public function validateBlock(ErrorElement $errorElement, BlockInterface $block)
    {
        $errorElement
            ->with('settings[url]')
                ->assertNotNull(array())
                ->assertNotBlank()
            ->end()
            ->with('settings[title]')
                ->assertNotNull(array())
                ->assertNotBlank()
                ->assertMaxLength(array('limit' => 50))
            ->end();
    }

    
    public function execute(BlockInterface $block, Response $response = null)
    {
                $settings = array_merge($this->getDefaultSettings(), $block->getSettings());

        $feeds = false;
        if ($settings['url']) {
            $options = array(
                'http' => array(
                    'user_agent' => 'Sonata/RSS Reader',
                    'timeout' => 2,
                )
            );

                        $content = @file_get_contents($settings['url'], false, stream_context_create($options));

            if ($content) {
                                try {
                    $feeds = new \SimpleXMLElement($content);
                    $feeds = $feeds->channel->item;
                } catch (\Exception $e) {
                                    }
            }
        }

        return $this->renderResponse('SonataBlockBundle:Block:block_core_rss.html.twig', array(
            'feeds'     => $feeds,
            'block'     => $block,
            'settings'  => $settings
        ), $response);
    }
}
}
 



namespace Sonata\BlockBundle\Block\Service
{

use Symfony\Component\HttpFoundation\Response;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Validator\ErrorElement;

use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\BlockBundle\Block\BaseBlockService;


class TextBlockService extends BaseBlockService
{
    
    public function execute(BlockInterface $block, Response $response = null)
    {
        $settings = array_merge($this->getDefaultSettings(), $block->getSettings());

        return $this->renderResponse('SonataBlockBundle:Block:block_core_text.html.twig', array(
            'block'     => $block,
            'settings'  => $settings
        ), $response);
    }

    
    public function validateBlock(ErrorElement $errorElement, BlockInterface $block)
    {
            }

    
    public function buildEditForm(FormMapper $formMapper, BlockInterface $block)
    {
        $formMapper->add('settings', 'sonata_type_immutable_array', array(
            'keys' => array(
                array('content', 'textarea', array()),
            )
        ));
    }

    
    public function getName()
    {
        return 'Text (core)';
    }

    
    public function getDefaultSettings()
    {
        return array(
            'content' => 'Insert your custom content here',
        );
    }
}
}
 


namespace Sonata\BlockBundle\Exception
{


interface BlockExceptionInterface
{
}
}
 



namespace Symfony\Component\HttpKernel\Exception
{


interface HttpExceptionInterface
{
    
    public function getStatusCode();

    
    public function getHeaders();
}
}
 



namespace Symfony\Component\HttpKernel\Exception
{


class HttpException extends \RuntimeException implements HttpExceptionInterface
{
    private $statusCode;
    private $headers;

    public function __construct($statusCode, $message = null, \Exception $previous = null, array $headers = array(), $code = 0)
    {
        $this->statusCode = $statusCode;
        $this->headers = $headers;

        parent::__construct($message, $code, $previous);
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getHeaders()
    {
        return $this->headers;
    }
}
}
 



namespace Symfony\Component\HttpKernel\Exception
{


class NotFoundHttpException extends HttpException
{
    
    public function __construct($message = null, \Exception $previous = null, $code = 0)
    {
        parent::__construct(404, $message, $previous, array(), $code);
    }
}
}
 


namespace Sonata\BlockBundle\Exception
{

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BlockNotFoundException extends NotFoundHttpException
{

}
}
 


namespace Sonata\BlockBundle\Exception\Filter
{

use Sonata\BlockBundle\Model\BlockInterface;


interface FilterInterface
{
    
    public function handle(\Exception $exception, BlockInterface $block);
}
}
 


namespace Sonata\BlockBundle\Exception\Filter
{

use Sonata\BlockBundle\Model\BlockInterface;


class DebugOnlyFilter implements FilterInterface
{
    
    protected $debug;

    
    public function __construct($debug)
    {
        $this->debug = $debug;
    }

    
    public function handle(\Exception $exception, BlockInterface $block)
    {
        return $this->debug ? true : false;
    }
}
}
 


namespace Sonata\BlockBundle\Exception\Filter
{

use Sonata\BlockBundle\Model\BlockInterface;


class IgnoreClassFilter implements FilterInterface
{
    
    protected $class;

    
    public function __construct($class)
    {
        $this->class = $class;
    }

    
    public function handle(\Exception $exception, BlockInterface $block)
    {
        return (!$exception instanceof $this->class);
    }
}
}
 


namespace Sonata\BlockBundle\Exception\Filter
{

use Sonata\BlockBundle\Model\BlockInterface;


class KeepAllFilter implements FilterInterface
{
    
    public function handle(\Exception $exception, BlockInterface $block)
    {
        return true;
    }
}
}
 


namespace Sonata\BlockBundle\Exception\Filter
{

use Sonata\BlockBundle\Model\BlockInterface;


class KeepNoneFilter implements FilterInterface
{
    
    public function handle(\Exception $exception, BlockInterface $block)
    {
        return false;
    }
}
}
 


namespace Sonata\BlockBundle\Exception\Renderer
{

use Symfony\Component\HttpFoundation\Response;
use Sonata\BlockBundle\Model\BlockInterface;


interface RendererInterface
{
    
    public function render(\Exception $exception, BlockInterface $block, Response $response = null);
}
}
 


namespace Sonata\BlockBundle\Exception\Renderer
{

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Templating\EngineInterface;
use Sonata\BlockBundle\Model\BlockInterface;

use Symfony\Component\HttpKernel\Exception\FlattenException;


class InlineDebugRenderer implements RendererInterface
{
    
    protected $templating;

    
    protected $template;

    
    protected $forceStyle;

    
    protected $debug;

    
    public function __construct(EngineInterface $templating, $template, $debug, $forceStyle = true)
    {
        $this->templating = $templating;
        $this->template   = $template;
        $this->debug      = $debug;
        $this->forceStyle = $forceStyle;
    }

    
    public function render(\Exception $exception, BlockInterface $block, Response $response = null)
    {
        $response = $response ?: new Response();

                if (!$this->debug) {
            return $response;
        }

        $flattenException = FlattenException::create($exception);
        $code = $flattenException->getStatusCode();

        $parameters = array(
            'exception'      => $flattenException,
            'status_code'    => $code,
            'status_text'    => isset(Response::$statusTexts[$code]) ? Response::$statusTexts[$code] : '',
            'logger'         => false,
            'currentContent' => false,
            'block'          => $block,
            'forceStyle'     => $this->forceStyle
        );

        $content = $this->templating->render($this->template, $parameters);
        $response->setContent($content);

        return $response;
    }
}
}
 


namespace Sonata\BlockBundle\Exception\Renderer
{

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Templating\EngineInterface;
use Sonata\BlockBundle\Model\BlockInterface;


class InlineRenderer implements RendererInterface
{
    
    protected $templating;

    
    protected $template;

    
    public function __construct(EngineInterface $templating, $template)
    {
        $this->templating = $templating;
        $this->template   = $template;
    }

    
    public function render(\Exception $exception, BlockInterface $block, Response $response = null)
    {
        $parameters = array(
            'exception'      => $exception,
            'block'          => $block
        );

        $content = $this->templating->render($this->template, $parameters);

        $response = $response ?: new Response();
        $response->setContent($content);

        return $response;
    }
}
}
 


namespace Sonata\BlockBundle\Exception\Renderer
{

use Symfony\Component\HttpFoundation\Response;
use Sonata\BlockBundle\Model\BlockInterface;


class MonkeyThrowRenderer implements RendererInterface
{
    
    public function render(\Exception $banana, BlockInterface $block, Response $response = null)
    {
        throw $banana;
    }
}
}
 


namespace Sonata\BlockBundle\Exception\Strategy
{

use Sonata\BlockBundle\Model\BlockInterface;
use Symfony\Component\HttpFoundation\Response;


interface StrategyManagerInterface
{
    
    public function handleException(\Exception $exception, BlockInterface $block, Response $response = null);
}
}
 


namespace Sonata\BlockBundle\Exception\Strategy
{

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\BlockBundle\Exception\Renderer\RendererInterface;
use Sonata\BlockBundle\Exception\Filter\FilterInterface;


class StrategyManager implements StrategyManagerInterface
{
    
    protected $container;

    
    protected $filters;

    
    protected $renderers;

    
    protected $blockFilters;

    
    protected $blockRenderers;

    
    protected $defaultFilter;

    
    protected $defaultRenderer;

    
    public function __construct(ContainerInterface $container, array $filters, array $renderers, array $blockFilters, array $blockRenderers)
    {
        $this->container      = $container;
        $this->filters        = $filters;
        $this->renderers      = $renderers;
        $this->blockFilters   = $blockFilters;
        $this->blockRenderers = $blockRenderers;
    }

    
    public function setDefaultFilter($name)
    {
        if (!array_key_exists($name, $this->filters)) {
            throw new \InvalidArgumentException(sprintf('Cannot set default exception filter "%s". It does not exists.', $name));
        }

        $this->defaultFilter = $name;
    }

    
    public function setDefaultRenderer($name)
    {
        if (!array_key_exists($name, $this->renderers)) {
            throw new \InvalidArgumentException(sprintf('Cannot set default exception renderer "%s". It does not exists.', $name));
        }

        $this->defaultRenderer = $name;
    }

    
    public function handleException(\Exception $exception, BlockInterface $block, Response $response = null)
    {
        $response = $response ?: new Response();
        $response->setPrivate();

        $filter = $this->getBlockFilter($block);
        if ($filter->handle($exception, $block)) {
            $renderer = $this->getBlockRenderer($block);
            $response = $renderer->render($exception, $block, $response);
        } else {
                    }

        return $response;
    }

    
    public function getBlockRenderer(BlockInterface $block)
    {
        $type = $block->getType();

        $name = isset($this->blockRenderers[$type]) ? $this->blockRenderers[$type] : $this->defaultRenderer;
        $service = $this->getRendererService($name);

        if (!$service instanceof RendererInterface) {
            throw new \RuntimeException(sprintf('The service "%s" is not an exception renderer', $name));
        }

        return $service;
    }

    
    public function getBlockFilter(BlockInterface $block)
    {
        $type = $block->getType();

        $name = isset($this->blockFilters[$type]) ? $this->blockFilters[$type] : $this->defaultFilter;
        $service = $this->getFilterService($name);

        if (!$service instanceof FilterInterface) {
            throw new \RuntimeException(sprintf('The service "%s" is not an exception filter', $name));
        }

        return $service;
    }

    
    protected function getFilterService($name)
    {
        if (!isset($this->filters[$name])) {
            throw new \RuntimeException('The filter "%s" does not exists.');
        }

        return $this->container->get($this->filters[$name]);
    }

    
    protected function getRendererService($name)
    {
        if (!isset($this->renderers[$name])) {
            throw new \RuntimeException('The renderer "%s" does not exists.');
        }

        return $this->container->get($this->renderers[$name]);
    }
}
}
 



namespace Sonata\BlockBundle\Form\Type
{

use Symfony\Component\Form\Exception\FormException;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Sonata\BlockBundle\Block\BlockServiceManagerInterface;

class ServiceListType extends AbstractType
{
    protected $manager;

    protected $contexts;

    
    public function __construct(BlockServiceManagerInterface $manager, array $contexts = array())
    {
        $this->manager  = $manager;
        $this->contexts = $contexts;
    }

    
    public function getName()
    {
        return 'sonata_block_service_choice';
    }

    
    public function getParent()
    {
        return 'choice';
    }

    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $contexts = $this->contexts;
        $manager = $this->manager;

        $resolver->setDefaults(array(
            'context'           => false,
            'multiple'          => false,
            'expanded'          => false,
            'choices'           => function (Options $options, $previousValue) use ($contexts, $manager) {
                if (!isset($options['context'])) {
                    throw new FormException('Please define a context option');
                }

                if (!isset($contexts[$options['context']])) {
                    throw new FormException('Invalid context');
                }

                $types = array();
                foreach ($contexts[$options['context']] as $service) {
                    $types[$service] = sprintf('%s - %s', $manager->getService($service)->getName(), $service);
                }

                return $types;
            },
            'preferred_choices' => array(),
            'empty_data'        => function (Options $options) {
                $multiple = isset($options['multiple']) && $options['multiple'];
                $expanded = isset($options['expanded']) && $options['expanded'];

                return $multiple || $expanded ? array() : '';
            },
            'empty_value'       => function (Options $options, $previousValue) {
                $multiple = isset($options['multiple']) && $options['multiple'];
                $expanded = isset($options['expanded']) && $options['expanded'];

                return $multiple || $expanded || !isset($previousValue) ? null : '';
            },
            'error_bubbling'    => false,
        ));
    }
}
}
 



namespace Sonata\BlockBundle\Model
{


interface BlockInterface
{
    
    public function setId($id);

    
    public function getId();

    
    public function setName($name);

    
    public function getName();

    
    public function setType($type);

    
    public function getType();

    
    public function setEnabled($enabled);

    
    public function getEnabled();

    
    public function setPosition($position);

    
    public function getPosition();

    
    public function setCreatedAt(\DateTime $createdAt = null);

    
    public function getCreatedAt();

    
    public function setUpdatedAt(\DateTime $updatedAt = null);

    
    public function getUpdatedAt();

    
    public function getTtl();

    
    public function __toString();

    
    public function setSettings(array $settings = array());

    
    public function getSettings();

    
    public function setSetting($name, $value);

    
    public function getSetting($name, $default = null);

    
    public function addChildren(BlockInterface $children);

    
    public function getChildren();

    
    public function hasChildren();

    
    public function setParent(BlockInterface $parent = null);

    
    public function getParent();

    
    public function hasParent();
}
}
 


namespace Sonata\BlockBundle\Model
{


abstract class BaseBlock implements BlockInterface
{
    
    protected $name;

    
    protected $settings;

    
    protected $enabled;

    
    protected $position;

    
    protected $parent;

    
    protected $children;

    
    protected $createdAt;

    
    protected $updatedAt;

    
    protected $type;

    
    protected $ttl;

    
    public function __construct()
    {
        $this->settings = array();
        $this->enabled  = false;
        $this->children = array();
    }

    
    public function setName($name)
    {
        $this->name = $name;
    }

    
    public function getName()
    {
        return $this->name;
    }

    
    public function setType($type)
    {
        $this->type = $type;
    }

    
    public function getType()
    {
        return $this->type;
    }

    
    public function setSettings(array $settings = array())
    {
        $this->settings = $settings;
    }

    
    public function getSettings()
    {
        return $this->settings;
    }

    
    public function setSetting($name, $value)
    {
        $this->settings[$name] = $value;
    }

    
    public function getSetting($name, $default = null)
    {
        return isset($this->settings[$name]) ? $this->settings[$name] : $default;
    }

    
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    
    public function getEnabled()
    {
        return $this->enabled;
    }

    
    public function setPosition($position)
    {
        $this->position = $position;
    }

    
    public function getPosition()
    {
        return $this->position;
    }

    
    public function setCreatedAt(\DateTime $createdAt = null)
    {
        $this->createdAt = $createdAt;
    }

    
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    
    public function setUpdatedAt(\DateTime $updatedAt = null)
    {
        $this->updatedAt = $updatedAt;
    }

    
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    
    public function addChildren(BlockInterface $child)
    {
        $this->children[] = $child;

        $child->setParent($this);
    }

    
    public function getChildren()
    {
        return $this->children;
    }

    
    public function setParent(BlockInterface $parent = null)
    {
        $this->parent = $parent;
    }

    
    public function getParent()
    {
        return $this->parent;
    }

    
    public function hasParent()
    {
        return $this->getParent() != null;
    }

    
    public function __toString()
    {
        return sprintf('block %d : "%s"', $this->getId(), $this->getname());
    }

    
    public function getTtl()
    {
        $ttl = $this->getSetting('ttl', 84600);

        foreach ($this->getChildren() as $block) {
            $blockTtl = $block->getTtl();

            $ttl = ($blockTtl < $ttl) ? $blockTtl : $ttl;
        }

        $this->ttl = $ttl;

        return $this->ttl;
    }

    
    public function hasChildren()
    {
        return count($this->children) > 0;
    }
}
}
 


namespace Sonata\BlockBundle\Model
{


class Block extends BaseBlock
{
    
    protected $id;

    
    public function setId($id)
    {
        $this->id = $id;
    }

    
    public function getId()
    {
        return $this->id;
    }
}
}
 


namespace Sonata\BlockBundle\Model
{

interface BlockManagerInterface
{
    
    public function create();

    
    public function delete(BlockInterface $block);

    
    public function findOneBy(array $criteria);

    
    public function findBy(array $criteria);

    
    public function getClass();

    
    public function save(BlockInterface $block);
}
}
 


namespace Sonata\BlockBundle\Model
{

use Sonata\BlockBundle\Model\Block;


class EmptyBlock extends Block
{
}
}
 



namespace Sonata\BlockBundle\Twig\Extension
{

use Sonata\BlockBundle\Block\BlockServiceManagerInterface;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\BlockBundle\Block\BlockLoaderInterface;
use Sonata\BlockBundle\Block\BlockRendererInterface;

use Sonata\CacheBundle\Cache\CacheManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class BlockExtension extends \Twig_Extension
{
    private $blockServiceManager;

    private $cacheManager;

    private $environment;

    private $cacheBlocks;

    private $blockLoader;

    private $blockRenderer;

    
    public function __construct(BlockServiceManagerInterface $blockServiceManager, array $cacheBlocks, BlockLoaderInterface $blockLoader, BlockRendererInterface $blockRenderer, CacheManagerInterface $cacheManager = null)
    {
        $this->blockServiceManager = $blockServiceManager;
        $this->cacheBlocks         = $cacheBlocks;
        $this->blockLoader         = $blockLoader;
        $this->blockRenderer       = $blockRenderer;
        $this->cacheManager        = $cacheManager;
    }

    
    public function getFunctions()
    {
        return array(
            'sonata_block_render'  => new \Twig_Function_Method($this, 'renderBlock', array('is_safe' => array('html'))),
            'sonata_block_include_javascripts'  => new \Twig_Function_Method($this, 'includeJavascripts', array('is_safe' => array('html'))),
            'sonata_block_include_stylesheets'  => new \Twig_Function_Method($this, 'includeStylesheets', array('is_safe' => array('html'))),
        );
    }

    
    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    
    public function getName()
    {
        return 'sonata_block';
    }

    
    public function includeJavascripts($media)
    {
        $javascripts = array();

        foreach ($this->blockServiceManager->getLoadedServices() as $service) {
            $javascripts = array_merge($javascripts, $service->getJavascripts($media));
        }

        if (count($javascripts) == 0) {
            return '';
        }

        $html = "";
        foreach ($javascripts as $javascript) {
            $html .= "\n" . sprintf('<script src="%s" type="text/javascript"></script>', $javascript);
        }

        return $html;
    }

    
    public function includeStylesheets($media)
    {
        $stylesheets = array();

        foreach ($this->blockServiceManager->getLoadedServices() as $service) {
            $stylesheets = array_merge($stylesheets, $service->getStylesheets($media));
        }

        if (count($stylesheets) == 0) {
            return '';
        }

        $html = sprintf("<style type='text/css' media='%s'>", $media);

        foreach ($stylesheets as $stylesheet) {
            $html .= "\n" . sprintf('@import url(%s);', $stylesheet, $media);
        }

        $html .= "\n</style>";

        return $html;
    }

    
    public function renderBlock($block, $useCache = true, array $extraCacheKeys = array())
    {
        if (!$block instanceof BlockInterface) {
            $block = $this->blockLoader->load($block);

                        if (!$block instanceof BlockInterface) {
                return '';
            }
        }

        $cacheKeys = false;
        $cacheService = $useCache ? $this->getCacheService($block) : false;
        if ($cacheService) {
            $cacheKeys = array_merge(
                $extraCacheKeys,
                $this->blockServiceManager->get($block)->getCacheKeys($block)
            );

            if ($cacheService->has($cacheKeys)) {
                $cacheElement = $cacheService->get($cacheKeys);
                if (!$cacheElement->isExpired() && $cacheElement->getData() instanceof Response) {
                    return $cacheElement->getData()->getContent();
                }
            }
        }

        $recorder = null;
        if ($this->cacheManager) {
            $recorder = $this->cacheManager->getRecorder();

            if ($recorder) {
                $recorder->add($block);
                $recorder->push();
            }
        }

        $response = $this->blockRenderer->render($block);
        $contextualKeys = $recorder ? $recorder->pop() : array();
        if ($response->isCacheable() && $cacheKeys && $cacheService) {
            $cacheService->set($cacheKeys, $response, $response->getTtl(), $contextualKeys);
        }

        return $response->getContent();
    }

    
    protected function getCacheService(BlockInterface $block)
    {
        if (!$this->cacheManager) {
            return false;
        }

        $type = isset($this->cacheBlocks[$block->getType()]) ? $this->cacheBlocks[$block->getType()] : false;

        if (!$type) {
            return false;
        }

        return $this->cacheManager->getCacheService($type);
    }
}
}
 



namespace Sonata\BlockBundle\Twig
{

use Symfony\Component\DependencyInjection\ContainerInterface;


class GlobalVariables
{
    protected $container;

    protected $templates;

    
    public function __construct(ContainerInterface $container, array $templates)
    {
        $this->container = $container;
        $this->templates = $templates;
    }

    
    public function getTemplates()
    {
        return $this->templates;
    }
}
}
 



namespace Sonata\MediaBundle\CDN
{

interface CDNInterface
{
    const STATUS_OK       = 1;
    const STATUS_TO_SEND  = 2;
    const STATUS_TO_FLUSH = 3;
    const STATUS_ERROR    = 4;
    const STATUS_WAITING  = 5;

    
    public function getPath($relativePath, $isFlushable);

    
    public function flush($string);

    
    public function flushByString($string);

    
    public function flushPaths(array $paths);
}
}
 



namespace Sonata\MediaBundle\CDN
{

class Fallback implements CDNInterface
{
    protected $cdn;

    protected $fallback;

    
    public function __construct(CDNInterface $cdn, CDNInterface $fallback)
    {
        $this->cdn      = $cdn;
        $this->fallback = $fallback;
    }

    
    public function getPath($relativePath, $isFlushable)
    {
        if ($isFlushable) {
            return $this->fallback->getPath($relativePath, $isFlushable);
        }

        return $this->cdn->getPath($relativePath, $isFlushable);
    }

    
    public function flushByString($string)
    {
        $this->cdn->flushByString($string);
    }

    
    public function flush($string)
    {
        $this->cdn->flush($string);
    }

    
    public function flushPaths(array $paths)
    {
        $this->cdn->flushPaths($paths);
    }
}
}
 


namespace Sonata\MediaBundle\CDN
{


class PantherPortal implements CDNInterface
{
    protected $path;

    protected $username;

    protected $password;

    protected $siteId;

    protected $client;

    protected $wsdl;

    
    public function __construct($path, $username, $password, $siteId, $wsdl = "https://pantherportal.cdnetworks.com/wsdl/flush.wsdl")
    {
        $this->path     = $path;
        $this->username = $username;
        $this->password = $password;
        $this->siteId   = $siteId;
        $this->wsdl     = $wsdl;
    }

    
    public function getPath($relativePath, $isFlushable)
    {
        return sprintf('%s/%s', $this->path, $relativePath);
    }

    
    public function flushByString($string)
    {
        $this->flushPaths(array($string));
    }

    
    public function flush($string)
    {
        $this->flushPaths(array($string));
    }

    
    public function flushPaths(array $paths)
    {
        $result = $this->getClient()->flush($this->username, $this->password, "paths", $this->siteId, implode("\n", $paths), true, false);

        if ($result != "Flush successfully submitted.") {
            throw new \RuntimeException('Unable to flush : ' . $result);
        }
    }

    
    private function getClient()
    {
        if (!$this->client) {
            $this->client = new \SoapClient($this->wsdl);
        }

        return $this->client;
    }

    
    public function setClient($client)
    {
        $this->client = $client;
    }
}
}
 



namespace Sonata\MediaBundle\CDN
{

class Server implements CDNInterface
{
    protected $path;

    
    public function __construct($path)
    {
        $this->path = $path;
    }

    
    public function getPath($relativePath, $isFlushable)
    {
        return sprintf('%s/%s', $this->path, $relativePath);
    }

    
    public function flushByString($string)
    {
            }

    
    public function flush($string)
    {
            }

    
    public function flushPaths(array $paths)
    {
            }
}
}
 



namespace Sonata\MediaBundle\Extra
{

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sonata\MediaBundle\Model\MediaInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sonata\MediaBundle\Model\MediaManagerInterface;
use Symfony\Component\Routing\RouterInterface;
use Sonata\MediaBundle\Provider\Pool;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Pixlr
{
    protected $referrer;

    protected $secret;

    protected $mediaManager;

    protected $router;

    protected $pool;

    protected $templating;

    protected $container;

    protected $validFormats;

    protected $allowEreg;

    
    public function __construct($referrer, $secret, Pool $pool, MediaManagerInterface $mediaManager, RouterInterface $router, EngineInterface $templating, ContainerInterface $container)
    {
        $this->referrer     = $referrer;
        $this->secret       = $secret;
        $this->mediaManager = $mediaManager;
        $this->router       = $router;
        $this->pool         = $pool;
        $this->templating   = $templating;
        $this->container    = $container;

        $this->validFormats = array('jpg', 'jpeg', 'png');
        $this->allowEreg    = '@http://([a-zA-Z0-9]*).pixlr.com/_temp/[0-9a-z]{24}\.[a-z]*@';
    }

    
    private function generateHash(MediaInterface $media)
    {
        return sha1($media->getId() . $media->getCreatedAt()->format('u') . $this->secret);
    }

    
    private function getMedia($id)
    {
        $media = $this->mediaManager->findOneBy(array('id' => $id));

        if (!$media) {
            throw new NotFoundHttpException('Media not found');
        }

        return $media;
    }

    
    private function checkMedia($hash, MediaInterface $media)
    {
        if ($hash != $this->generateHash($media)) {
            throw new NotFoundHttpException('Invalid hash');
        }

        if (!$this->isEditable($media)) {
            throw new NotFoundHttpException('Media is not editable');
        }
    }

    
    private function buildQuery(array $parameters = array())
    {
        $query = array();
        foreach ($parameters as $name => $value) {
            $query[] = sprintf('%s=%s', $name, $value);
        }

        return implode('&', $query);
    }

    
    public function editAction($id, $mode)
    {
        if (!in_array($mode, array('express', 'editor'))) {
            throw new NotFoundHttpException('Invalid mode');
        }

        $media = $this->getMedia($id);

        $provider = $this->pool->getProvider($media->getProviderName());

        $hash = $this->generateHash($media);

        $parameters = array(
            's'          => 'c',             'referrer'   => $this->referrer,
            'exit'       => $this->router->generate('sonata_media_pixlr_exit', array('hash' => $hash, 'id' => $media->getId()), true),
            'image'      => $provider->generatePublicUrl($media, 'reference'),
            'title'      => $media->getName(),
            'target'     => $this->router->generate('sonata_media_pixlr_target', array('hash' => $hash, 'id' => $media->getId()), true),
            'locktitle'  => true,
            'locktarget' => true,
        );

        $url = sprintf('http://pixlr.com/%s/?%s', $mode, $this->buildQuery($parameters));

        return new RedirectResponse($url);
    }

    
    public function exitAction($hash, $id)
    {
        $media = $this->getMedia($id);

        $this->checkMedia($hash, $media);

        return new RedirectResponse($this->router->generate('admin_sonata_media_media_edit', array('id' => $media->getId())));
    }

    
    public function targetAction(Request $request, $hash, $id)
    {
        $media = $this->getMedia($id);

        $this->checkMedia($hash, $media);

        $provider = $this->pool->getProvider($media->getProviderName());

        
        if (!preg_match($this->allowEreg, $request->get('image'), $matches)) {
            throw new NotFoundHttpException(sprintf('Invalid image host : %s', $request->get('image')));
        }

        $file = $provider->getReferenceFile($media);
        $file->setContent(file_get_contents($request->get('image')));

        $provider->updateMetadata($media);
        $provider->generateThumbnails($media);

        $this->mediaManager->save($media);

        return new RedirectResponse($this->router->generate('admin_sonata_media_media_view', array('id' => $media->getId())));
    }

    
    public function isEditable(MediaInterface $media)
    {
        if (!$this->container->get('sonata.media.admin.media')->isGranted('EDIT', $media)) {
            return false;
        }

        return in_array(strtolower($media->getExtension()), $this->validFormats);
    }

    
    public function openEditorAction($id)
    {
        $media = $this->getMedia($id);

        if (!$this->isEditable($media)) {
            throw new NotFoundHttpException('The media is not editable');
        }

        return new Response($this->templating->render('SonataMediaBundle:Extra:pixlr_editor.html.twig', array(
            'media' => $media
        )));
    }
}
}
 

namespace Gaufrette\Adapter
{


interface ChecksumCalculator
{
    
    public function checksum($key);
}
}
 

namespace Gaufrette\Adapter
{


interface StreamFactory
{
    
    public function createStream($key);
}
}
 

namespace Gaufrette
{


interface Adapter
{
    
    public function read($key);

    
    public function write($key, $content);

    
    public function exists($key);

    
    public function keys();

    
    public function mtime($key);

    
    public function delete($key);

    
    public function rename($sourceKey, $targetKey);

    
    public function isDirectory($key);
}
}
 

namespace Gaufrette\Adapter
{

use Gaufrette\Util;
use Gaufrette\Adapter;
use Gaufrette\Stream;
use Gaufrette\Adapter\StreamFactory;
use Gaufrette\Exception;


class Local implements Adapter,
                       StreamFactory,
                       ChecksumCalculator
{
    protected $directory;
    private $create;

    
    public function __construct($directory, $create = false)
    {
        $this->directory = Util\Path::normalize($directory);

        if (is_link($this->directory)) {
            $this->directory = realpath($this->directory);
        }

        $this->create = $create;
    }

    
    public function read($key)
    {
        return file_get_contents($this->computePath($key));
    }

    
    public function write($key, $content)
    {
        $path = $this->computePath($key);
        $this->ensureDirectoryExists(dirname($path), true);

        return file_put_contents($path, $content);
    }

    
    public function rename($sourceKey, $targetKey)
    {
        $targetPath = $this->computePath($targetKey);
        $this->ensureDirectoryExists(dirname($targetPath), true);

        return rename($this->computePath($sourceKey), $targetPath);
    }

    
    public function exists($key)
    {
        return file_exists($this->computePath($key));
    }

    
    public function keys()
    {
        $this->ensureDirectoryExists($this->directory, $this->create);

        try {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator(
                    $this->directory,
                    \FilesystemIterator::SKIP_DOTS | \FilesystemIterator::UNIX_PATHS
                )
            );
        } catch (\Exception $e) {
            $iterator = new \EmptyIterator;
        }
        $files = iterator_to_array($iterator);

        $keys = array();
        foreach ($files as $file) {
            $keys[] = $key = $this->computeKey($file);
            if ('.' !== dirname($key)) {
                $keys[] = dirname($key);
            }
        }
        sort($keys);

        return $keys;
    }

    
    public function mtime($key)
    {
        return filemtime($this->computePath($key));
    }

    
    public function delete($key)
    {
        if ($this->isDirectory($key)) {
            return rmdir($this->computePath($key));
        }

        return unlink($this->computePath($key));
    }

    
    public function isDirectory($key)
    {
        return is_dir($this->computePath($key));
    }

    
    public function createStream($key)
    {
        return new Stream\Local($this->computePath($key));
    }

    public function checksum($key)
    {
        return Util\Checksum::fromFile($this->computePath($key));
    }

    
    public function computeKey($path)
    {
        $path = $this->normalizePath($path);

        return ltrim(substr($path, strlen($this->directory)), '/');
    }

    
    protected function computePath($key)
    {
        $this->ensureDirectoryExists($this->directory, $this->create);

        return $this->normalizePath($this->directory . '/' . $key);
    }

    
    protected function normalizePath($path)
    {
        $path = Util\Path::normalize($path);

        if (0 !== strpos($path, $this->directory)) {
            throw new \OutOfBoundsException(sprintf('The path "%s" is out of the filesystem.', $path));
        }

        return $path;
    }

    
    protected function ensureDirectoryExists($directory, $create = false)
    {
        if (!is_dir($directory)) {
            if (!$create) {
                throw new \RuntimeException(sprintf('The directory "%s" does not exist.', $directory));
            }

            $this->createDirectory($directory);
        }
    }

    
    protected function createDirectory($directory)
    {
        $umask = umask(0);
        $created = mkdir($directory, 0777, true);
        umask($umask);

        if (!$created) {
            throw new \RuntimeException(sprintf('The directory \'%s\' could not be created.', $directory));
        }
    }
}
}
 


namespace Sonata\MediaBundle\Filesystem
{

use Gaufrette\Adapter\Local as BaseLocal;

class Local extends BaseLocal
{
    public function getDirectory()
    {
        return $this->directory;
    }
}
}
 

namespace Gaufrette\Adapter
{


interface MetadataSupporter
{
    
    public function setMetadata($key, $content);

    
    public function getMetadata($key);
}
}
 


namespace Sonata\MediaBundle\Filesystem
{

use Gaufrette\Adapter as AdapterInterface;
use Gaufrette\Adapter\MetadataSupporter;
use Gaufrette\Filesystem;

class Replicate implements AdapterInterface, MetadataSupporter
{
    protected $master;

    protected $slave;

    
    public function __construct(AdapterInterface $master, AdapterInterface $slave)
    {
        $this->master = $master;
        $this->slave  = $slave;
    }

    
    public function delete($key)
    {
        return $this->slave->delete($key) && $this->master->delete($key);
    }

    
    public function mtime($key)
    {
        return $this->master->mtime($key);
    }

    
    public function keys()
    {
        return $this->master->keys();
    }

    
    public function exists($key)
    {
        return $this->master->exists($key);
    }

    
    public function write($key, $content, array $metadata = null)
    {
        $return = $this->master->write($key, $content, $metadata);
        $this->slave->write($key, $content, $metadata);

        return $return;
    }

    
    public function read($key)
    {
        return $this->master->read($key);
    }

    
    public function rename($key, $new)
    {
        $this->master->rename($key, $new);
        $this->slave->rename($key, $new);
    }

    
    public function supportsMetadata()
    {
        return $this->master instanceof MetadataSupporter ||  $this->slave instanceof MetadataSupporter;
    }

    
    public function setMetadata($key, $metadata)
    {
        if ($this->master instanceof MetadataSupporter) {
            $this->master->setMetadata($key, $metadata);
        }
        if ($this->slave instanceof MetadataSupporter) {
            $this->slave->setMetadata($key, $metadata);
        }
    }

    
    public function getMetadata($key)
    {
        if ($this->master instanceof MetadataSupporter) {
            return $this->master->getMetadata($key);
        } elseif ($this->slave instanceof MetadataSupporter) {
            return $this->slave->getMetadata($key);
        }

        return array();
    }

    
    public function getAdapterClassNames()
    {
        return array(
            get_class($this->master),
            get_class($this->slave),
        );
    }

    
    public function createFile($key, Filesystem $filesystem)
    {
        return $this->master->createFile($key, $filesystem);
    }

    
    public function createFileStream($key, Filesystem $filesystem)
    {
        return $this->master->createFileStream($key, $filesystem);
    }

    
    public function listDirectory($directory = '')
    {
        return $this->master->listDirectory($directory);
    }

    
    public function isDirectory($key)
    {
        return $this->master->isDirectory($key);
    }
}
}
 


namespace Sonata\MediaBundle\Generator
{

use Sonata\MediaBundle\Model\MediaInterface;

interface GeneratorInterface
{

    
    public function generatePath(MediaInterface $media);
}
}
 


namespace Sonata\MediaBundle\Generator
{

use Sonata\MediaBundle\Model\MediaInterface;

class DefaultGenerator implements GeneratorInterface
{

    protected $firstLevel;

    protected $secondLevel;

    
    public function __construct($firstLevel = 100000, $secondLevel = 1000)
    {
        $this->firstLevel = $firstLevel;
        $this->secondLevel = $secondLevel;
    }

    
    public function generatePath(MediaInterface $media)
    {
        $rep_first_level = (int) ($media->getId() / $this->firstLevel);
        $rep_second_level = (int) (($media->getId() - ($rep_first_level * $this->firstLevel)) / $this->secondLevel);

        return sprintf('%s/%04s/%02s', $media->getContext(), $rep_first_level + 1, $rep_second_level + 1);
    }
}
}
 


namespace Sonata\MediaBundle\Generator
{

use Sonata\MediaBundle\Model\MediaInterface;

class ODMGenerator implements GeneratorInterface
{
    
    public function generatePath(MediaInterface $media)
    {
        $id = $media->getId();

        return sprintf('%s/%04s/%02s', $media->getContext(), substr($id, 0, 4), substr($id, 4, 2));
    }
}
}
 


namespace Sonata\MediaBundle\Generator
{

use Sonata\MediaBundle\Model\MediaInterface;

class PHPCRGenerator implements GeneratorInterface
{
    
    public function generatePath(MediaInterface $media)
    {
        $segments = preg_split('#/#', $media->getId(), null, PREG_SPLIT_NO_EMPTY);

        if (count($segments) > 1) {
                        array_pop($segments);
            $path = join($segments, '/');
        } else {
            $path = '';
        }

        return $path ? sprintf('%s/%s', $media->getContext(), $path) : $media->getContext();
    }
}}
 



namespace Sonata\MediaBundle\Metadata
{

use Sonata\MediaBundle\Model\MediaInterface;

interface MetadataBuilderInterface
{

    
    public function get(MediaInterface $media, $filename);
}
}
 



namespace Sonata\MediaBundle\Metadata
{

use Sonata\MediaBundle\Metadata\MetadataBuilderInterface;
use Sonata\MediaBundle\Model\MediaInterface;
use \AmazonS3 as AmazonS3;
use \CFMimeTypes;

class AmazonMetadataBuilder implements MetadataBuilderInterface
{

    protected $settings;

    protected $acl = array(
        'private' => AmazonS3::ACL_PRIVATE,
        'public' => AmazonS3::ACL_PUBLIC,
        'open' => AmazonS3::ACL_OPEN,
        'auth_read' => AmazonS3::ACL_AUTH_READ,
        'owner_read' => AmazonS3::ACL_OWNER_READ,
        'owner_full_control' => AmazonS3::ACL_OWNER_FULL_CONTROL,
    );

    
    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }

    
    protected function getDefaultMetadata()
    {
                $output = array();
        if (isset($this->settings['acl']) && !empty($this->settings['acl'])) {
            $output['acl'] = $this->acl[$this->settings['acl']];
        }

                if (isset($this->settings['storage'])) {
            if ($this->settings['storage'] == 'standard') {
                $output['storage'] = AmazonS3::STORAGE_STANDARD;
            } elseif ($this->settings['storage'] == 'reduced') {
                $output['storage'] = AmazonS3::STORAGE_REDUCED;
            }
        }

                if (isset($this->settings['meta']) && !empty($this->settings['meta'])) {
            $output['meta'] = $this->settings['meta'];
        }

                if (isset($this->settings['cache_control']) && !empty($this->settings['cache_control'])) {
            $output['headers']['Cache-Control'] = $this->settings['cache_control'];
        }

                if (isset($this->settings['encryption']) && !empty($this->settings['encryption'])) {
            if ($this->settings['encryption'] == 'aes256') {
                $output['encryption'] = 'AES256';
            }
        }

        return $output;
    }

    
    protected function getContentType($filename)
    {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $contentType = CFMimeTypes::get_mimetype($extension);

        return array('contentType'=> $contentType);
    }

    
    public function get(MediaInterface $media, $filename)
    {
        return array_replace_recursive(
            $this->getDefaultMetadata(),
            $this->getContentType($filename)
        );
    }
}
}
 



namespace Sonata\MediaBundle\Metadata
{

use Sonata\MediaBundle\Metadata\MetadataBuilderInterface;
use Sonata\MediaBundle\Model\MediaInterface;

class NoopMetadataBuilder implements MetadataBuilderInterface
{
    
    public function get(MediaInterface $media, $filename)
    {
        return array();
    }
}
}
 



namespace Sonata\MediaBundle\Metadata
{

use Sonata\MediaBundle\Metadata\MetadataBuilderInterface;
use Sonata\MediaBundle\Model\MediaInterface;
use Sonata\MediaBundle\Filesystem\Replicate;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ProxyMetadataBuilder implements MetadataBuilderInterface
{
    private $container;
    private $map;
    private $metadata;

    
    public function __construct(ContainerInterface $container, array $map)
    {
        $this->container = $container;
        $this->map = $map;
    }

    
    public function get(MediaInterface $media, $filename)
    {
                if (!$this->container->has($media->getProviderName())) {
            return array();
        }

        if ($meta = $this->getAmazonBuilder($media, $filename)) {
            return $meta;
        }

        if (!$this->container->has('sonata.media.metadata.noop')) {
            return array();
        }

        return $this->container->get('sonata.media.metadata.noop')->get($media, $filename);
    }

    
    protected function getAmazonBuilder(MediaInterface $media, $filename)
    {
        $adapter = $this->container->get($media->getProviderName())->getFilesystem()->getAdapter();

                if ($adapter instanceof Replicate) {
            $adapterClassNames = $adapter->getAdapterClassNames();
        } else {
            $adapterClassNames = array(get_class($adapter));
        }

                if (!in_array('Gaufrette\Adapter\AmazonS3', $adapterClassNames) || !$this->container->has('sonata.media.metadata.amazon')) {
            return false;
        }

        return $this->container->get('sonata.media.metadata.amazon')->get($media, $filename);;
    }
}
}
 


namespace Sonata\MediaBundle\Model
{

interface GalleryInterface
{
    
    public function setName($name);

    
    public function getContext();

    
    public function setContext($context);

    
    public function getName();

    
    public function setEnabled($enabled);

    
    public function getEnabled();

    
    public function setUpdatedAt(\DateTime $updatedAt = null);

    
    public function getUpdatedAt();

    
    public function setCreatedAt(\DateTime $createdAt = null);

    
    public function getCreatedAt();

    
    public function setDefaultFormat($defaultFormat);

    
    public function getDefaultFormat();

    
    public function setGalleryHasMedias($galleryHasMedias);

    
    public function getGalleryHasMedias();

    
    public function addGalleryHasMedias(GalleryHasMediaInterface $galleryHasMedia);

    
    public function __toString();
}
}
 


namespace Sonata\MediaBundle\Model
{

use Doctrine\Common\Collections\ArrayCollection;

abstract class Gallery implements GalleryInterface
{
    
    protected $context;

    
    protected $name;

    
    protected $enabled;

    
    protected $updatedAt;

    
    protected $createdAt;

    protected $defaultFormat;

    protected $galleryHasMedias;

    
    public function setName($name)
    {
        $this->name = $name;
    }

    
    public function getName()
    {
        return $this->name;
    }

    
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    
    public function getEnabled()
    {
        return $this->enabled;
    }

    
    public function setUpdatedAt(\DateTime $updatedAt = null)
    {
        $this->updatedAt = $updatedAt;
    }

    
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    
    public function setCreatedAt(\DateTime $createdAt = null)
    {
        $this->createdAt = $createdAt;
    }

    
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    
    public function setDefaultFormat($defaultFormat)
    {
        $this->defaultFormat = $defaultFormat;
    }

    
    public function getDefaultFormat()
    {
        return $this->defaultFormat;
    }

    
    public function setGalleryHasMedias($galleryHasMedias)
    {
        $this->galleryHasMedias = new ArrayCollection();

        foreach ($galleryHasMedias as $galleryHasMedia) {
            $this->addGalleryHasMedias($galleryHasMedia);
        }
    }

    
    public function getGalleryHasMedias()
    {
        return $this->galleryHasMedias;
    }

    
    public function addGalleryHasMedias(GalleryHasMediaInterface $galleryHasMedia)
    {
        $galleryHasMedia->setGallery($this);

        $this->galleryHasMedias[] = $galleryHasMedia;
    }

    
    public function __toString()
    {
        return $this->getName() ?: '-';
    }

    
    public function setContext($context)
    {
        $this->context = $context;
    }

    
    public function getContext()
    {
        return $this->context;
    }
}
}
 



namespace Sonata\MediaBundle\Model
{

interface GalleryHasMediaInterface
{
    
    public function setEnabled($enabled);

    
    public function getEnabled();

    
    public function setGallery(GalleryInterface $gallery = null);

    
    public function getGallery();

    
    public function setMedia(MediaInterface $media = null);

    
    public function getMedia();

    
    public function setPosition($position);

    
    public function getPosition();

    
    public function setUpdatedAt(\DateTime $updatedAt = null);

    
    public function getUpdatedAt();

    
    public function setCreatedAt(\DateTime $createdAt = null);

    
    public function getCreatedAt();

    
    public function __toString();
}
}
 



namespace Sonata\MediaBundle\Model
{

abstract class GalleryHasMedia implements GalleryHasMediaInterface
{
    protected $media;

    protected $gallery;

    protected $position;

    protected $updatedAt;

    protected $createdAt;

    protected $enabled;

    public function __construct()
    {
        $this->position = 0;
        $this->enabled  = false;
    }

    
    public function setCreatedAt(\DateTime $createdAt = null)
    {
        $this->createdAt = $createdAt;
    }

    
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    
    public function getEnabled()
    {
        return $this->enabled;
    }

    
    public function setGallery(GalleryInterface $gallery = null)
    {
        $this->gallery = $gallery;
    }

    
    public function getGallery()
    {
        return $this->gallery;
    }

    
    public function setMedia(MediaInterface $media = null)
    {
        $this->media = $media;
    }

    
    public function getMedia()
    {
        return $this->media;
    }

    
    public function setPosition($position)
    {
        $this->position = $position;
    }

    
    public function getPosition()
    {
        return $this->position;
    }

    
    public function setUpdatedAt(\DateTime $updatedAt = null)
    {
        $this->updatedAt = $updatedAt;
    }

    
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    
    public function __toString()
    {
        return $this->getGallery().' | '.$this->getMedia();
    }
}
}
 



namespace Sonata\MediaBundle\Model
{

interface GalleryManagerInterface
{
    
    public function create();

    
    public function delete(GalleryInterface $gallery);

    
    public function findOneBy(array $criteria);

    
    public function findBy(array $criteria);

    
    public function getClass();

    
    public function update(GalleryInterface $gallery);
}
}
 



namespace Sonata\MediaBundle\Model
{

abstract class GalleryManager implements GalleryManagerInterface
{
    
    public function create()
    {
        $class = $this->getClass();

        return new $class;
    }
}
}
 



namespace Sonata\MediaBundle\Model
{

interface MediaInterface
{
    const STATUS_OK          = 1;
    const STATUS_SENDING     = 2;
    const STATUS_PENDING     = 3;
    const STATUS_ERROR       = 4;
    const STATUS_ENCODING    = 5;

    
    public function setBinaryContent($binaryContent);

    
    public function getBinaryContent();

    
    public function getMetadataValue($name, $default = null);

    
    public function setMetadataValue($name, $value);

    
    public function unsetMetadataValue($name);

    
    public function getId();

    
    public function setName($name);

    
    public function getName();

    
    public function setDescription($description);

    
    public function getDescription();

    
    public function setEnabled($enabled);

    
    public function getEnabled();

    
    public function setProviderName($providerName);

    
    public function getProviderName();

    
    public function setProviderStatus($providerStatus);

    
    public function getProviderStatus();

    
    public function setProviderReference($providerReference);

    
    public function getProviderReference();

    
    public function setProviderMetadata(array $providerMetadata = array());

    
    public function getProviderMetadata();

    
    public function setWidth($width);

    
    public function getWidth();

    
    public function setHeight($height);

    
    public function getHeight();

    
    public function setLength($length);

    
    public function getLength();

    
    public function setCopyright($copyright);

    
    public function getCopyright();

    
    public function setAuthorName($authorName);

    
    public function getAuthorName();

    
    public function setContext($context);

    
    public function getContext();

    
    public function setCdnIsFlushable($cdnIsFlushable);

    
    public function getCdnIsFlushable();

    
    public function setCdnFlushAt(\Datetime $cdnFlushAt = null);

    
    public function getCdnFlushAt();

    
    public function setUpdatedAt(\Datetime $updatedAt = null);

    
    public function getUpdatedAt();

    
    public function setCreatedAt(\Datetime $createdAt = null);

    
    public function getCreatedAt();

    
    public function setContentType($contentType);

    
    public function getExtension();

    
    public function getContentType();

    
    public function setSize($size);

    
    public function getSize();

    
    public function setCdnStatus($cdnStatus);

    
    public function getCdnStatus();

    
    public function getBox();

    
    public function __toString();

    
    public function setGalleryHasMedias($galleryHasMedias);

    
    public function getGalleryHasMedias();

    
    public function getPreviousProviderReference();
}
}
 


namespace Sonata\MediaBundle\Model
{

use Imagine\Image\Box;

abstract class Media implements MediaInterface
{
    
    protected $name;

    
    protected $description;

    
    protected $enabled = false;

    
    protected $providerName;

    
    protected $providerStatus;

    
    protected $providerReference;

    
    protected $providerMetadata = array();

    
    protected $width;

    
    protected $height;

    
    protected $length;

    
    protected $copyright;

    
    protected $authorName;

    
    protected $context;

    
    protected $cdnIsFlushable;

    
    protected $cdnFlushAt;

    
    protected $cdnStatus;

    
    protected $updatedAt;

    
    protected $createdAt;

    protected $binaryContent;

    protected $previousProviderReference;

    
    protected $contentType;

    
    protected $size;

    protected $galleryHasMedias;

    public function prePersist()
    {
        $this->setCreatedAt(new \DateTime);
        $this->setUpdatedAt(new \DateTime);
    }

    public function preUpdate()
    {
        $this->setUpdatedAt(new \DateTime);
    }

    
    public static function getStatusList()
    {
        return array(
            self::STATUS_OK          => 'ok',
            self::STATUS_SENDING     => 'sending',
            self::STATUS_PENDING     => 'pending',
            self::STATUS_ERROR       => 'error',
            self::STATUS_ENCODING    => 'encoding',
        );
    }

    
    public function setBinaryContent($binaryContent)
    {
        $this->previousProviderReference = $this->providerReference;
        $this->providerReference = null;
        $this->binaryContent = $binaryContent;
    }

    
    public function getBinaryContent()
    {
        return $this->binaryContent;
    }

    
    public function getMetadataValue($name, $default = null)
    {
        $metadata = $this->getProviderMetadata();

        return isset($metadata[$name]) ? $metadata[$name] : $default;
    }

    
    public function setMetadataValue($name, $value)
    {
        $metadata = $this->getProviderMetadata();
        $metadata[$name] = $value;
        $this->setProviderMetadata($metadata);
    }

    
    public function unsetMetadataValue($name)
    {
        $metadata = $this->getProviderMetadata();
        unset($metadata[$name]);
        $this->setProviderMetadata($metadata);
    }

    
    public function setName($name)
    {
        $this->name = $name;
    }

    
    public function getName()
    {
        return $this->name;
    }

    
    public function setDescription($description)
    {
        $this->description = $description;
    }

    
    public function getDescription()
    {
        return $this->description;
    }

    
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    
    public function getEnabled()
    {
        return $this->enabled;
    }

    
    public function setProviderName($providerName)
    {
        $this->providerName = $providerName;
    }

    
    public function getProviderName()
    {
        return $this->providerName;
    }

    
    public function setProviderStatus($providerStatus)
    {
        $this->providerStatus = $providerStatus;
    }

    
    public function getProviderStatus()
    {
        return $this->providerStatus;
    }

    
    public function setProviderReference($providerReference)
    {
        $this->providerReference = $providerReference;
    }

    
    public function getProviderReference()
    {
        return $this->providerReference;
    }

    
    public function setProviderMetadata(array $providerMetadata = array())
    {
        $this->providerMetadata = $providerMetadata;
    }

    
    public function getProviderMetadata()
    {
        return $this->providerMetadata;
    }

    
    public function setWidth($width)
    {
        $this->width = $width;
    }

    
    public function getWidth()
    {
        return $this->width;
    }

    
    public function setHeight($height)
    {
        $this->height = $height;
    }

    
    public function getHeight()
    {
        return $this->height;
    }

    
    public function setLength($length)
    {
        $this->length = $length;
    }

    
    public function getLength()
    {
        return $this->length;
    }

    
    public function setCopyright($copyright)
    {
        $this->copyright = $copyright;
    }

    
    public function getCopyright()
    {
        return $this->copyright;
    }

    
    public function setAuthorName($authorName)
    {
        $this->authorName = $authorName;
    }

    
    public function getAuthorName()
    {
        return $this->authorName;
    }

    
    public function setContext($context)
    {
        $this->context = $context;
    }

    
    public function getContext()
    {
        return $this->context;
    }

    
    public function setCdnIsFlushable($cdnIsFlushable)
    {
        $this->cdnIsFlushable = $cdnIsFlushable;
    }

    
    public function getCdnIsFlushable()
    {
        return $this->cdnIsFlushable;
    }

    
    public function setCdnFlushAt(\DateTime $cdnFlushAt = null)
    {
        $this->cdnFlushAt = $cdnFlushAt;
    }

    
    public function getCdnFlushAt()
    {
        return $this->cdnFlushAt;
    }

    
    public function setUpdatedAt(\DateTime $updatedAt = null)
    {
        $this->updatedAt = $updatedAt;
    }

    
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    
    public function setCreatedAt(\DateTime $createdAt = null)
    {
        $this->createdAt = $createdAt;
    }

    
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
    }

    
    public function getContentType()
    {
        return $this->contentType;
    }

    
    public function getExtension()
    {
        return pathinfo($this->getProviderReference(), PATHINFO_EXTENSION);
    }

    
    public function setSize($size)
    {
        $this->size = $size;
    }

    
    public function getSize()
    {
        return $this->size;
    }

    
    public function setCdnStatus($cdnStatus)
    {
        $this->cdnStatus = $cdnStatus;
    }

    
    public function getCdnStatus()
    {
        return $this->cdnStatus;
    }

    
    public function getBox()
    {
        return new Box($this->width, $this->height);
    }

    
    public function __toString()
    {
        return $this->getName() ?: 'n/a';
    }

    
    public function setGalleryHasMedias($galleryHasMedias)
    {
        $this->galleryHasMedias = $galleryHasMedias;
    }

    
    public function getGalleryHasMedias()
    {
        return $this->galleryHasMedias;
    }

    
    public function getPreviousProviderReference()
    {
        return $this->previousProviderReference;
    }
}
}
 



namespace Sonata\MediaBundle\Model
{

interface MediaManagerInterface
{
    
    public function create();

    
    public function delete(MediaInterface $media);

    
    public function findBy(array $criteria);

    
    public function findOneBy(array $criteria);

    
    public function getClass();

    
    public function save(MediaInterface $media, $context = null, $providerName = null);
}
}
 



namespace Sonata\MediaBundle\Model
{

use Sonata\MediaBundle\Provider\Pool;

abstract class MediaManager implements MediaManagerInterface
{
    
    protected $pool;

    
    protected $class;

    
    public function __construct(Pool $pool, $class)
    {
        $this->pool  = $pool;
        $this->class = $class;
    }

    
    public function create()
    {
        $class = $this->getClass();

        return new $class;
    }

    
    public function getPool()
    {
        return $this->pool;
    }

    
    public function findOneBy(array $criteria)
    {
        return $this->getRepository()->findOneBy($criteria);
    }

    
    public function findBy(array $criteria)
    {
        return $this->getRepository()->findBy($criteria);
    }

    
    public function getClass()
    {
        return $this->class;
    }
}
}
 


namespace Sonata\MediaBundle\Provider
{

use Sonata\MediaBundle\Model\MediaInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\MediaBundle\Resizer\ResizerInterface;
use Gaufrette\Filesystem;
use Sonata\AdminBundle\Validator\ErrorElement;
use Symfony\Component\Form\FormBuilder;

interface MediaProviderInterface
{
    
    public function addFormat($name, $format);

    
    public function getFormat($name);

    
    public function requireThumbnails();

    
    public function generateThumbnails(MediaInterface $media);

    
    public function removeThumbnails(MediaInterface $media);

    
    public function getReferenceFile(MediaInterface $media);

    
    public function getFormatName(MediaInterface $media, $format);

    
    public function getReferenceImage(MediaInterface $media);

    
    public function preUpdate(MediaInterface $media);

    
    public function postUpdate(MediaInterface $media);

    
    public function preRemove(MediaInterface $media);

    
    public function postRemove(MediaInterface $media);

    
    public function buildCreateForm(FormMapper $formMapper);

    
    public function buildEditForm(FormMapper $formMapper);

    
    public function prePersist(MediaInterface $media);

    
    public function postPersist(MediaInterface $media);

    
    public function getHelperProperties(MediaInterface $media, $format);

    
    public function generatePath(MediaInterface $media);

    
    public function generatePublicUrl(MediaInterface $media, $format);

    
    public function generatePrivateUrl(MediaInterface $media, $format);

    
    public function getFormats();

    
    public function setName($name);

    
    public function getName();

    
    public function setTemplates(array $templates);

    
    public function getTemplates();

    
    public function getTemplate($name);

    
    public function getDownloadResponse(MediaInterface $media, $format, $mode, array $headers = array());

    
    public function getResizer();

    
    public function getFilesystem();

    
    public function getCdnPath($relativePath, $isFlushable);

    
    public function transform(MediaInterface $media);

    
    public function validate(ErrorElement $errorElement, MediaInterface $media);

    
    public function buildMediaType(FormBuilder $formBuilder);

    
    public function updateMetadata(MediaInterface $media, $force = false);
}
}
 



namespace Sonata\MediaBundle\Provider
{

use Gaufrette\Filesystem;
use Sonata\MediaBundle\Resizer\ResizerInterface;
use Sonata\MediaBundle\Model\MediaInterface;
use Sonata\MediaBundle\CDN\CDNInterface;
use Sonata\MediaBundle\Generator\GeneratorInterface;
use Sonata\MediaBundle\Thumbnail\ThumbnailInterface;
use Sonata\AdminBundle\Validator\ErrorElement;

abstract class BaseProvider implements MediaProviderInterface
{
    
    protected $formats = array();

    protected $templates = array();

    protected $resizer;

    protected $filesystem;

    protected $pathGenerator;

    protected $cdn;

    protected $thumbnail;

    
    public function __construct($name, Filesystem $filesystem, CDNInterface $cdn, GeneratorInterface $pathGenerator, ThumbnailInterface $thumbnail)
    {
        $this->name          = $name;
        $this->filesystem    = $filesystem;
        $this->cdn           = $cdn;
        $this->pathGenerator = $pathGenerator;
        $this->thumbnail     = $thumbnail;
    }

    
    abstract protected function doTransform(MediaInterface $media);

    
    final public function transform(MediaInterface $media)
    {
        if (null === $media->getBinaryContent()) {
            return;
        }

        $this->doTransform($media);
    }

    
    public function addFormat($name, $format)
    {
        $this->formats[$name] = $format;
    }

    
    public function getFormat($name)
    {
        return isset($this->formats[$name]) ? $this->formats[$name] : false;
    }

    
    public function requireThumbnails()
    {
        return $this->getResizer() !== null;
    }

    
    public function generateThumbnails(MediaInterface $media)
    {
        $this->thumbnail->generate($this, $media);
    }

    
    public function removeThumbnails(MediaInterface $media)
    {
        $this->thumbnail->delete($this, $media);
    }

    
    public function getFormatName(MediaInterface $media, $format)
    {
        if ($format == 'admin') {
            return 'admin';
        }

        if ($format == 'reference') {
            return 'reference';
        }

        $baseName = $media->getContext().'_';
        if (substr($format, 0, strlen($baseName)) == $baseName) {
            return $format;
        }

        return $baseName.$format;
    }

    
    public function preRemove(MediaInterface $media)
    {
        $path = $this->getReferenceImage($media);

        if ($this->getFilesystem()->has($path)) {
            $this->getFilesystem()->delete($path);
        }

        $this->thumbnail->delete($this, $media);
    }

    
    public function generatePath(MediaInterface $media)
    {
        return $this->pathGenerator->generatePath($media);
    }

    
    public function getFormats()
    {
        return $this->formats;
    }

    
    public function setName($name)
    {
        $this->name = $name;
    }

    
    public function getName()
    {
        return $this->name;
    }

    
    public function setTemplates(array $templates)
    {
        $this->templates = $templates;
    }

    
    public function getTemplates()
    {
        return $this->templates;
    }

    
    public function getTemplate($name)
    {
        return isset($this->templates[$name]) ? $this->templates[$name] : null;
    }

    
    public function getResizer()
    {
        return $this->resizer;
    }

    
    public function getFilesystem()
    {
        return $this->filesystem;
    }

    
    public function getCdn()
    {
        return $this->cdn;
    }

    
    public function getCdnPath($relativePath, $isFlushable)
    {
        return $this->getCdn()->getPath($relativePath, $isFlushable);
    }

    
    public function setResizer(ResizerInterface $resizer)
    {
        $this->resizer = $resizer;
    }

    
    public function prePersist(MediaInterface $media)
    {
        $media->setCreatedAt(new \Datetime());
        $media->setUpdatedAt(new \Datetime());
    }

    
    public function preUpdate(MediaInterface $media)
    {
        $media->setUpdatedAt(new \Datetime());
    }

    
    public function validate(ErrorElement $errorElement, MediaInterface $media)
    {

    }
}
}
 



namespace Sonata\MediaBundle\Provider
{

use Gaufrette\Filesystem;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\MediaBundle\Model\MediaInterface;
use Sonata\MediaBundle\CDN\CDNInterface;
use Sonata\MediaBundle\Generator\GeneratorInterface;
use Buzz\Browser;
use Sonata\MediaBundle\Thumbnail\ThumbnailInterface;
use Symfony\Component\Form\FormBuilder;
use Sonata\MediaBundle\Metadata\MetadataBuilderInterface;

abstract class BaseVideoProvider extends BaseProvider
{
    protected $browser;
    protected $metadata;

    
    public function __construct($name, Filesystem $filesystem, CDNInterface $cdn, GeneratorInterface $pathGenerator, ThumbnailInterface $thumbnail, Browser $browser, MetadataBuilderInterface $metadata = null)
    {
        parent::__construct($name, $filesystem, $cdn, $pathGenerator, $thumbnail);

        $this->browser = $browser;
        $this->metadata = $metadata;
    }

    
    public function getReferenceImage(MediaInterface $media)
    {
        return $media->getMetadataValue('thumbnail_url');
    }

    
    public function getReferenceFile(MediaInterface $media)
    {
        $key = $this->generatePrivateUrl($media, 'reference');

                if ($this->getFilesystem()->has($key)) {
            $referenceFile = $this->getFilesystem()->get($key);
        } else {
            $referenceFile = $this->getFilesystem()->get($key, true);
            $metadata = $this->metadata ? $this->metadata->get($media, $referenceFile->getName()) : array();
            $referenceFile->setContent(file_get_contents($this->getReferenceImage($media)), $metadata);
        }

        return $referenceFile;
    }

    
    public function generatePublicUrl(MediaInterface $media, $format)
    {
        return $this->getCdn()->getPath(sprintf('%s/thumb_%d_%s.jpg',
            $this->generatePath($media),
            $media->getId(),
            $format
        ), $media->getCdnIsFlushable());
    }

    
    public function generatePrivateUrl(MediaInterface $media, $format)
    {
        return sprintf('%s/thumb_%d_%s.jpg',
            $this->generatePath($media),
            $media->getId(),
            $format
        );
    }

    
    public function buildEditForm(FormMapper $formMapper)
    {
        $formMapper->add('name');
        $formMapper->add('enabled', null, array('required' => false));
        $formMapper->add('authorName');
        $formMapper->add('cdnIsFlushable');
        $formMapper->add('description');
        $formMapper->add('copyright');
        $formMapper->add('binaryContent', 'text', array('required' => false));
    }

    
    public function buildCreateForm(FormMapper $formMapper)
    {
        $formMapper->add('binaryContent', 'text');
    }

    
    public function buildMediaType(FormBuilder $formBuilder)
    {
        $formBuilder->add('binaryContent', 'text');
    }

    
    public function postUpdate(MediaInterface $media)
    {
        $this->postPersist($media);
    }

    
    public function postPersist(MediaInterface $media)
    {
        if (!$media->getBinaryContent()) {
            return;
        }

        $this->generateThumbnails($media);
    }

    
    public function postRemove(MediaInterface $media)
    {
    }

    
    protected function getMetadata(MediaInterface $media, $url)
    {
        try {
            $response = $this->browser->get($url);
        } catch (\RuntimeException $e) {
            throw new \RuntimeException('Unable to retrieve the video information for :' . $url, null, $e);
        }

        $metadata = json_decode($response->getContent(), true);

        if (!$metadata) {
            throw new \RuntimeException('Unable to decode the video information for :' . $url);
        }

        return $metadata;
    }

    
    protected function getBoxHelperProperties(MediaInterface $media, $format, $options = array())
    {
        if ($format == 'reference') {
            return $media->getBox();
        }

        if (isset($options['width']) || isset($options['height'])) {
            $settings = array(
                'width'  => isset($options['width']) ? $options['width'] : null,
                'height' => isset($options['height']) ? $options['height'] : null,
            );

        } else {
            $settings = $this->getFormat($format);
        }

        return $this->resizer->getBox($media, $settings);
    }
}
}
 



namespace Sonata\MediaBundle\Provider
{

use Sonata\MediaBundle\Model\MediaInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DailyMotionProvider extends BaseVideoProvider
{
    
    public function getHelperProperties(MediaInterface $media, $format, $options = array())
    {
        
        $defaults = array(
                                    'related'           => 0,

                                                'explicit'          => 0,

                                    'autoPlay'          => 0,

                        'autoMute'          => 0,

                                    'unmuteOnMouseOver' => 0,

                                    'start'             => 0,

                                                'enableApi'         => 0,

                                    'chromeless'        => 0,

                                    'expendVideo'       => 0,
            'color2'            => null,

                                    'foreground'        => null,
            'background'        => null,
            'highlight'         => null,
        );

        $player_parameters = array_merge($defaults, isset($options['player_parameters']) ? $options['player_parameters'] : array());

        $box = $this->getBoxHelperProperties($media, $format, $options);

        $params = array(
            'player_parameters' => http_build_query($player_parameters),
            'allowFullScreen'   => isset($options['allowFullScreen']) ? $options['allowFullScreen'] : 'true',
            'allowScriptAccess' => isset($options['allowScriptAccess']) ? $options['allowScriptAccess'] : 'always',
            'width'             => $box->getWidth(),
            'height'            => $box->getHeight(),
        );

        return $params;
    }

    
    protected function fixBinaryContent(MediaInterface $media)
    {
        if (!$media->getBinaryContent()) {
            return;
        }

        if (preg_match("/www.dailymotion.com\/video\/([0-9a-zA-Z]*)_/", $media->getBinaryContent(), $matches)) {
            $media->setBinaryContent($matches[1]);
        }
    }

    
    protected function doTransform(MediaInterface $media)
    {
        $this->fixBinaryContent($media);

        if (!$media->getBinaryContent()) {
            return;
        }

        $media->setProviderName($this->name);
        $media->setProviderStatus(MediaInterface::STATUS_OK);
        $media->setProviderReference($media->getBinaryContent());

        $this->updateMetadata($media, true);
    }

    
    public function updateMetadata(MediaInterface $media, $force = false)
    {
        $url = sprintf('http://www.dailymotion.com/services/oembed?url=http://www.dailymotion.com/video/%s&format=json', $media->getProviderReference());

        try {
            $metadata = $this->getMetadata($media, $url);
        } catch (\RuntimeException $e) {
            $media->setEnabled(false);
            $media->setProviderStatus(MediaInterface::STATUS_ERROR);

            return;
        }

        $media->setProviderMetadata($metadata);

        if ($force) {
            $media->setName($metadata['title']);
            $media->setAuthorName($metadata['author_name']);
        }

        $media->setHeight($metadata['height']);
        $media->setWidth($metadata['width']);
    }

    
    public function getDownloadResponse(MediaInterface $media, $format, $mode, array $headers = array())
    {
        return new RedirectResponse(sprintf('http://www.dailymotion.com/video/%s', $media->getProviderReference()), 302, $headers);
    }
}
}
 



namespace Sonata\MediaBundle\Provider
{

use Sonata\MediaBundle\Model\MediaInterface;
use Gaufrette\Adapter\Local;
use Sonata\MediaBundle\CDN\CDNInterface;
use Sonata\MediaBundle\Generator\GeneratorInterface;
use Sonata\MediaBundle\Thumbnail\ThumbnailInterface;
use Sonata\MediaBundle\Metadata\MetadataBuilderInterface;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Validator\ErrorElement;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Form\FormBuilder;

use Gaufrette\Filesystem;

class FileProvider extends BaseProvider
{
    protected $allowedExtensions;

    protected $allowedMimeTypes;

    protected $metadata;

    
    public function __construct($name, Filesystem $filesystem, CDNInterface $cdn, GeneratorInterface $pathGenerator, ThumbnailInterface $thumbnail, array $allowedExtensions = array(), array $allowedMimeTypes = array(), MetadataBuilderInterface $metadata = null)
    {
        parent::__construct($name, $filesystem, $cdn, $pathGenerator, $thumbnail);

        $this->allowedExtensions = $allowedExtensions;
        $this->allowedMimeTypes  = $allowedMimeTypes;
        $this->metadata = $metadata;
    }

    
    public function getReferenceImage(MediaInterface $media)
    {
        return sprintf('%s/%s',
            $this->generatePath($media),
            $media->getProviderReference()
        );
    }

    
    public function getReferenceFile(MediaInterface $media)
    {
        return $this->getFilesystem()->get($this->getReferenceImage($media), true);
    }

    
    public function buildEditForm(FormMapper $formMapper)
    {
        $formMapper->add('name');
        $formMapper->add('enabled', null, array('required' => false));
        $formMapper->add('authorName');
        $formMapper->add('cdnIsFlushable');
        $formMapper->add('description');
        $formMapper->add('copyright');
        $formMapper->add('binaryContent', 'file', array('required' => false));
    }

    
    public function buildCreateForm(FormMapper $formMapper)
    {
        $formMapper->add('binaryContent', 'file');
    }

    
    public function buildMediaType(FormBuilder $formBuilder)
    {
        $formBuilder->add('binaryContent', 'file');
    }

    
    public function postPersist(MediaInterface $media)
    {
        if ($media->getBinaryContent() === null) {
            return;
        }

        $this->setFileContents($media);

        $this->generateThumbnails($media);
    }

    
    public function postUpdate(MediaInterface $media)
    {
        if (!$media->getBinaryContent() instanceof \SplFileInfo) {
            return;
        }

                $oldMedia = clone $media;
        $oldMedia->setProviderReference($media->getPreviousProviderReference());

        $path = $this->getReferenceImage($oldMedia);

        if ($this->getFilesystem()->has($path)) {
            $this->getFilesystem()->delete($path);
        }

        $this->fixBinaryContent($media);

        $this->setFileContents($media);

        $this->generateThumbnails($media);
    }

    
    protected function fixBinaryContent(MediaInterface $media)
    {
        if ($media->getBinaryContent() === null) {
            return;
        }

                if (!$media->getBinaryContent() instanceof File) {
            if (!is_file($media->getBinaryContent())) {
                throw new \RuntimeException('The file does not exist : ' . $media->getBinaryContent());
            }

            $binaryContent = new File($media->getBinaryContent());

            $media->setBinaryContent($binaryContent);
        }
    }

    
    protected function fixFilename(MediaInterface $media)
    {
        if ($media->getBinaryContent() instanceof UploadedFile) {
            $media->setName($media->getName() ?: $media->getBinaryContent()->getClientOriginalName());
            $media->setMetadataValue('filename', $media->getBinaryContent()->getClientOriginalName());
        } elseif ($media->getBinaryContent() instanceof File) {
            $media->setName($media->getName() ?: $media->getBinaryContent()->getBasename());
            $media->setMetadataValue('filename', $media->getBinaryContent()->getBasename());
        }

                if (!$media->getName()) {
            throw new \RuntimeException('Please define a valid media\'s name');
        }
    }

    
    protected function doTransform(MediaInterface $media)
    {
        $this->fixBinaryContent($media);
        $this->fixFilename($media);

                if (!$media->getProviderReference()) {
            $media->setProviderReference($this->generateReferenceName($media));
        }

        if ($media->getBinaryContent()) {
            $media->setContentType($media->getBinaryContent()->getMimeType());
            $media->setSize($media->getBinaryContent()->getSize());
        }

        $media->setProviderStatus(MediaInterface::STATUS_OK);
    }

    
    public function updateMetadata(MediaInterface $media, $force = true)
    {
                $path = tempnam(sys_get_temp_dir(), 'sonata_update_metadata');
        $fileObject = new \SplFileObject($path, 'w');
        $fileObject->fwrite($this->getReferenceFile($media)->getContent());

        $media->setSize($fileObject->getSize());
    }

    
    public function generatePublicUrl(MediaInterface $media, $format)
    {
        if ($format == 'reference') {
            $path = $this->getReferenceImage($media);
        } else {
            $path = sprintf('media_bundle/images/files/%s/file.png', $format);
        }

        return $this->getCdn()->getPath($path, $media->getCdnIsFlushable());
    }

    
    public function getHelperProperties(MediaInterface $media, $format, $options = array())
    {
        return array_merge(array(
            'title'       => $media->getName(),
            'thumbnail'   => $this->getReferenceImage($media),
            'file'        => $this->getReferenceImage($media),
        ), $options);
    }

    
    public function generatePrivateUrl(MediaInterface $media, $format)
    {
        return false;
    }

    
    public function preRemove(MediaInterface $media)
    {
            }

    
    protected function setFileContents(MediaInterface $media, $contents = null)
    {
        $file = $this->getFilesystem()->get(sprintf('%s/%s', $this->generatePath($media), $media->getProviderReference()), true);

        if (!$contents) {
            $contents = $media->getBinaryContent()->getRealPath();
        }

        $metadata = $this->metadata ? $this->metadata->get($media, $file->getName()) : array();
        $file->setContent(file_get_contents($contents), $metadata);
    }

    
    public function postRemove(MediaInterface $media)
    {
            }

    
    protected function generateReferenceName(MediaInterface $media)
    {
        return sha1($media->getName() . rand(11111, 99999)) . '.' . $media->getBinaryContent()->guessExtension();
    }

    
    public function getDownloadResponse(MediaInterface $media, $format, $mode, array $headers = array())
    {
                $headers = array_merge(array(
            'Content-Type'          => $media->getContentType(),
            'Content-Disposition'   => sprintf('attachment; filename="%s"', $media->getMetadataValue('filename')),
        ), $headers);

        if (!in_array($mode, array('http', 'X-Sendfile', 'X-Accel-Redirect'))) {
            throw new \RuntimeException('Invalid mode provided');
        }

        if ($mode == 'http') {
            $provider = $this;

            return new StreamedResponse(function() use ($provider, $media) {
                echo $provider->getReferenceFile($media)->getContent();
            }, 200, $headers);
        }

        if (!$this->getFilesystem()->getAdapter() instanceof \Sonata\MediaBundle\Filesystem\Local) {
            throw new \RuntimeException('Cannot use X-Sendfile or X-Accel-Redirect with non \Sonata\MediaBundle\Filesystem\Local');
        }

        $headers[$mode] = sprintf('%s/%s',
            $this->getFilesystem()->getAdapter()->getDirectory(),
            $this->generatePrivateUrl($media, $format)
        );

        return new Response('', 200, $headers);
    }

    
    public function validate(ErrorElement $errorElement, MediaInterface $media)
    {
        if (!$media->getBinaryContent() instanceof \SplFileInfo) {
            return;
        }

        if ($media->getBinaryContent() instanceof UploadedFile) {
            $fileName = $media->getBinaryContent()->getClientOriginalName();
        } elseif ($media->getBinaryContent() instanceof File) {
            $fileName = $media->getBinaryContent()->getFilename();
        } else {
            throw new \RuntimeException(sprintf('Invalid binary content type: %s', get_class($media->getBinaryContent())));
        }

        if (!in_array(strtolower(pathinfo($fileName, PATHINFO_EXTENSION)), $this->allowedExtensions)) {
            $errorElement
                ->with('binaryContent')
                    ->addViolation('Invalid extensions')
                ->end();
        }

        if (!in_array($media->getBinaryContent()->getMimeType(), $this->allowedMimeTypes)) {
            $errorElement
                ->with('binaryContent')
                    ->addViolation('Invalid mime type : ' . $media->getBinaryContent()->getMimeType())
                ->end();
        }
    }
}
}
 


namespace Sonata\MediaBundle\Provider
{

use Sonata\MediaBundle\Model\MediaInterface;
use Sonata\MediaBundle\CDN\CDNInterface;
use Sonata\MediaBundle\Generator\GeneratorInterface;
use Sonata\MediaBundle\Thumbnail\ThumbnailInterface;
use Sonata\MediaBundle\Metadata\MetadataBuilderInterface;

use Imagine\Image\ImagineInterface;
use Gaufrette\Filesystem;

class ImageProvider extends FileProvider
{
    protected $imagineAdapter;

    
    public function __construct($name, Filesystem $filesystem, CDNInterface $cdn, GeneratorInterface $pathGenerator, ThumbnailInterface $thumbnail, array $allowedExtensions = array(), array $allowedMimeTypes = array(), ImagineInterface $adapter, MetadataBuilderInterface $metadata = null)
    {
        parent::__construct($name, $filesystem, $cdn, $pathGenerator, $thumbnail, $allowedExtensions, $allowedMimeTypes, $metadata);

        $this->imagineAdapter = $adapter;
    }

    
    public function getHelperProperties(MediaInterface $media, $format, $options = array())
    {
        if ($format == 'reference') {
            $box = $media->getBox();
        } else {
            $resizerFormat = $this->getFormat($format);
            if ($resizerFormat === false) {
                throw new \RuntimeException(sprintf('The image format "%s" is not defined.
                        Is the format registered in your sonata-media configuration?', $format));
            }

            $box = $this->resizer->getBox($media, $resizerFormat);
        }

        return array_merge(array(
            'title'    => $media->getName(),
            'src'      => $this->generatePublicUrl($media, $format),
            'width'    => $box->getWidth(),
            'height'   => $box->getHeight()
        ), $options);
    }

    
    public function getReferenceImage(MediaInterface $media)
    {
        return sprintf('%s/%s',
            $this->generatePath($media),
            $media->getProviderReference()
        );
    }

    
    protected function doTransform(MediaInterface $media)
    {
        parent::doTransform($media);

        if ($media->getBinaryContent()) {
            $image = $this->imagineAdapter->open($media->getBinaryContent()->getPathname());
            $size  = $image->getSize();

            $media->setWidth($size->getWidth());
            $media->setHeight($size->getHeight());

            $media->setProviderStatus(MediaInterface::STATUS_OK);
        }
    }

    
    public function updateMetadata(MediaInterface $media, $force = true)
    {
        try {
                        $path       = tempnam(sys_get_temp_dir(), 'sonata_update_metadata');
            $fileObject = new \SplFileObject($path, 'w');
            $fileObject->fwrite($this->getReferenceFile($media)->getContent());

            $image = $this->imagineAdapter->open($fileObject->getPathname());
            $size  = $image->getSize();

            $media->setSize($fileObject->getSize());
            $media->setWidth($size->getWidth());
            $media->setHeight($size->getHeight());
        } catch (\LogicException $e) {
            $media->setProviderStatus(MediaInterface::STATUS_ERROR);

            $media->setSize(0);
            $media->setWidth(0);
            $media->setHeight(0);
        }
    }

    
    public function generatePublicUrl(MediaInterface $media, $format)
    {
        if ($format == 'reference') {
            $path = $this->getReferenceImage($media);
        } else {
            $path = $this->thumbnail->generatePublicUrl($this, $media, $format);
        }

        return $this->getCdn()->getPath($path, $media->getCdnIsFlushable());
    }

    
    public function generatePrivateUrl(MediaInterface $media, $format)
    {
        return $this->thumbnail->generatePrivateUrl($this, $media, $format);
    }

    
    public function preRemove(MediaInterface $media)
    {
        $path = $this->getReferenceImage($media);

        if ($this->getFilesystem()->has($path)) {
            $this->getFilesystem()->delete($path);
        }

        $this->thumbnail->delete($this, $media);
    }
}
}
 



namespace Sonata\MediaBundle\Provider
{

use Sonata\MediaBundle\Model\MediaInterface;
use Sonata\MediaBundle\Provider\MediaProviderInterface;
use Sonata\MediaBundle\Security\DownloadStrategyInterface;
use Sonata\AdminBundle\Validator\ErrorElement;

class Pool
{
    
    protected $providers = array();

    protected $contexts = array();

    protected $downloadSecurities = array();

    protected $defaultContext;

    
    public function __construct($context)
    {
        $this->defaultContext = $context;
    }

    
    public function getProvider($name)
    {
        if (!isset($this->providers[$name])) {
            throw new \RuntimeException(sprintf('unable to retrieve the provider named : `%s`', $name));
        }

        return $this->providers[$name];
    }

    
    public function addProvider($name, MediaProviderInterface $instance)
    {
        $this->providers[$name] = $instance;
    }

    
    public function addDownloadSecurity($name, DownloadStrategyInterface $security)
    {
        $this->downloadSecurities[$name] = $security;
    }

    
    public function setProviders($providers)
    {
        $this->providers = $providers;
    }

    
    public function getProviders()
    {
        return $this->providers;
    }

    
    public function addContext($name, array $providers = array(), array $formats = array(), array $download = array())
    {
        if (!$this->hasContext($name)) {
            $this->contexts[$name] = array(
                'providers' => array(),
                'formats'   => array(),
                'download'  => array(),
            );
        }

        $this->contexts[$name]['providers'] = $providers;
        $this->contexts[$name]['formats']   = $formats;
        $this->contexts[$name]['download']  = $download;
    }

    
    public function hasContext($name)
    {
        return isset($this->contexts[$name]);
    }

    
    public function getContext($name)
    {
        if (!$this->hasContext($name)) {
            return null;
        }

        return $this->contexts[$name];
    }

    
    public function getContexts()
    {
        return $this->contexts;
    }

    
    public function getProviderNamesByContext($name)
    {
        $context = $this->getContext($name);

        if (!$context) {
            return null;
        }

        return $context['providers'];
    }

    
    public function getFormatNamesByContext($name)
    {
        $context = $this->getContext($name);

        if (!$context) {
            return null;
        }

        return $context['formats'];
    }

    
    public function getProvidersByContext($name)
    {
        $providers = array();

        if (!$this->hasContext($name)) {
            return $providers;
        }

        foreach ($this->getProviderNamesByContext($name) as $name) {
            $providers[] = $this->getProvider($name);
        }

        return $providers;
    }

    
    public function getProviderList()
    {
        $choices = array();
        foreach (array_keys($this->providers) as $name) {
            $choices[$name] = $name;
        }

        return $choices;
    }

    
    public function getDownloadSecurity(MediaInterface $media)
    {
        $context = $this->getContext($media->getContext());

        $id = $context['download']['strategy'];

        if (!isset($this->downloadSecurities[$id])) {
            throw new \RuntimeException('Unable to retrieve the download security : ' . $id);
        }

        return $this->downloadSecurities[$id];
    }

    
    public function getDownloadMode(MediaInterface $media)
    {
        $context = $this->getContext($media->getContext());

        return $context['download']['mode'];
    }

    
    public function getDefaultContext()
    {
        return $this->defaultContext;
    }

    
    public function validate(ErrorElement $errorElement, MediaInterface $media)
    {
        $provider = $this->getProvider($media->getProviderName());

        $provider->validate($errorElement, $media);
    }
}
}
 



namespace Sonata\MediaBundle\Provider
{

use Sonata\MediaBundle\Model\MediaInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class VimeoProvider extends BaseVideoProvider
{
    
    public function getHelperProperties(MediaInterface $media, $format, $options = array())
    {
                $defaults = array(
                                    'fp_version'      => 10,

                        'fullscreen'      => true,

                        'title'           => true,

                        'byline'          => 0,

                        'portrait'        => true,

                        'color'           => null,

                        'hd_off'          => 0,

                        'js_api'          => null,

                        'js_onLoad'       => 0,

                        'js_swf_id'       => uniqid('vimeo_player_'),
        );

        $player_parameters = array_merge($defaults, isset($options['player_parameters']) ? $options['player_parameters'] : array());

        $box = $this->getBoxHelperProperties($media, $format, $options);

        $params = array(
            'src'         => http_build_query($player_parameters),
            'id'          => $player_parameters['js_swf_id'],
            'frameborder' => isset($options['frameborder']) ? $options['frameborder'] : 0,
            'width'       => $box->getWidth(),
            'height'      => $box->getHeight(),
        );

        return $params;
    }

    
    protected function fixBinaryContent(MediaInterface $media)
    {
        if (!$media->getBinaryContent()) {
            return;
        }

        if (preg_match("/vimeo\.com\/(\d+)/", $media->getBinaryContent(), $matches)) {
            $media->setBinaryContent($matches[1]);
        }
    }

    
    protected function doTransform(MediaInterface $media)
    {
        $this->fixBinaryContent($media);

        if (!$media->getBinaryContent()) {
            return;
        }

                $media->setProviderName($this->name);
        $media->setProviderReference($media->getBinaryContent());
        $media->setProviderStatus(MediaInterface::STATUS_OK);

        $this->updateMetadata($media, true);
    }

    
    public function updateMetadata(MediaInterface $media, $force = false)
    {
        $url = sprintf('http://vimeo.com/api/oembed.json?url=http://vimeo.com/%s', $media->getProviderReference());

        try {
            $metadata = $this->getMetadata($media, $url);
        } catch (\RuntimeException $e) {
            $media->setEnabled(false);
            $media->setProviderStatus(MediaInterface::STATUS_ERROR);

            return;
        }

                $media->setProviderMetadata($metadata);

                if ($force) {
            $media->setName($metadata['title']);
            $media->setDescription($metadata['description']);
            $media->setAuthorName($metadata['author_name']);
        }

        $media->setHeight($metadata['height']);
        $media->setWidth($metadata['width']);
        $media->setLength($metadata['duration']);
        $media->setContentType('video/x-flv');
    }

    
    public function getDownloadResponse(MediaInterface $media, $format, $mode, array $headers = array())
    {
        return new RedirectResponse(sprintf('http://vimeo.com/%s', $media->getProviderReference()), 302, $headers);
    }
}
}
 



namespace Sonata\MediaBundle\Provider
{

use Sonata\MediaBundle\Model\MediaInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class YouTubeProvider extends BaseVideoProvider
{
    
    public function getHelperProperties(MediaInterface $media, $format, $options = array())
    {
        
        $defaults = array(
                                                            'rel'            => 0,

                                    'autoplay'       => 0,

                                                            'loop'           => 0,

                                                'enablejsapi'    => 0,

                                    'playerapiid'    => null,

                                                                                                'disablekb'      => 0,

                                                'egm'            => 0,

                                                'border'         => 0,

                                    'color1'         => null,
            'color2'         => null,

                                                                        'start'          => 0,

                                                'fs'             => 1,

                                                                        'hd'             => 1,

                                                'showsearch'     => 0,

                                    'showinfo'       => 0,

                                    'iv_load_policy' => 1,

                                    'cc_load_policy' => 1
        );

        $player_parameters = array_merge($defaults, isset($options['player_parameters']) ? $options['player_parameters'] : array());

        $box = $this->getBoxHelperProperties($media, $format, $options);

        $params = array(
            'player_parameters' => http_build_query($player_parameters),
            'allowFullScreen'   => $player_parameters['fs'] == '1' ? 'true' : 'false',
            'allowScriptAccess' => isset($options['allowScriptAccess']) ? $options['allowScriptAccess'] : 'always',
            'width'             => $box->getWidth(),
            'height'            => $box->getHeight(),
        );

        return $params;
    }

    
    protected function fixBinaryContent(MediaInterface $media)
    {
        if (!$media->getBinaryContent()) {
            return;
        }

        if (preg_match("/(?<=v(\=|\/))([-a-zA-Z0-9_]+)|(?<=youtu\.be\/)([-a-zA-Z0-9_]+)/", $media->getBinaryContent(), $matches)) {
            $media->setBinaryContent($matches[2]);
        }
    }

    
    protected function doTransform(MediaInterface $media)
    {
        $this->fixBinaryContent($media);

        if (!$media->getBinaryContent()) {
            return;
        }

        $media->setProviderName($this->name);
        $media->setProviderStatus(MediaInterface::STATUS_OK);
        $media->setProviderReference($media->getBinaryContent());

        $this->updateMetadata($media, true);
    }

    
    public function updateMetadata(MediaInterface $media, $force = false)
    {
        $url = sprintf('http://www.youtube.com/oembed?url=http://www.youtube.com/watch?v=%s&format=json', $media->getProviderReference());

        try {
            $metadata = $this->getMetadata($media, $url);
        } catch (\RuntimeException $e) {
            $media->setEnabled(false);
            $media->setProviderStatus(MediaInterface::STATUS_ERROR);

            return;
        }

        $media->setProviderMetadata($metadata);

        if ($force) {
            $media->setName($metadata['title']);
            $media->setAuthorName($metadata['author_name']);
        }

        $media->setHeight($metadata['height']);
        $media->setWidth($metadata['width']);
        $media->setContentType('video/x-flv');
    }

    
    public function getDownloadResponse(MediaInterface $media, $format, $mode, array $headers = array())
    {
        return new RedirectResponse(sprintf('http://www.youtube.com/watch?v=%s', $media->getProviderReference()), 302, $headers);
    }
}
}
 



namespace Sonata\MediaBundle\Resizer
{

use Gaufrette\File;
use Sonata\MediaBundle\Model\MediaInterface;

interface ResizerInterface
{
    
    public function resize(MediaInterface $media, File $in, File $out, $format, array $settings);

    
    public function getBox(MediaInterface $media, array $settings);
}
}
 



namespace Sonata\MediaBundle\Resizer
{

use Imagine\Image\ImagineInterface;
use Imagine\Image\Box;
use Gaufrette\File;
use Sonata\MediaBundle\Model\MediaInterface;
use Imagine\Image\ImageInterface;
use Imagine\Exception\InvalidArgumentException;
use Sonata\MediaBundle\Metadata\MetadataBuilderInterface;

class SimpleResizer implements ResizerInterface
{
    protected $adapter;

    protected $mode;

    protected $metadata;

    
    public function __construct(ImagineInterface $adapter, $mode, MetadataBuilderInterface $metadata)
    {
        $this->adapter  = $adapter;
        $this->mode     = $mode;
        $this->metadata = $metadata;
    }

    
    public function resize(MediaInterface $media, File $in, File $out, $format, array $settings)
    {
        if (!isset($settings['width'])) {
            throw new \RuntimeException(sprintf('Width parameter is missing in context "%s" for provider "%s"', $media->getContext(), $media->getProviderName()));
        }

        $image = $this->adapter->load($in->getContent());

        $content = $image
            ->thumbnail($this->getBox($media, $settings), $this->mode)
            ->get($format, array('quality' => $settings['quality']));

        $out->setContent($content, $this->metadata->get($media, $out->getName()));
    }

    
    public function getBox(MediaInterface $media, array $settings)
    {
        $size = $media->getBox();

        if ($settings['width'] == null && $settings['height'] == null) {
            throw new \RuntimeException(sprintf('Width/Height parameter is missing in context "%s" for provider "%s". Please add at least one parameter.', $media->getContext(), $media->getProviderName()));
        }

        if ($settings['height'] == null) {
            $settings['height'] = (int) ($settings['width'] * $size->getHeight() / $size->getWidth());
        }

        if ($settings['width'] == null) {
            $settings['width'] = (int) ($settings['height'] * $size->getWidth() / $size->getHeight());
        }

        return $this->computeBox($media, $settings);
    }

    
    private function computeBox(MediaInterface $media, array $settings)
    {
        if ($this->mode !== ImageInterface::THUMBNAIL_INSET && $this->mode !== ImageInterface::THUMBNAIL_OUTBOUND) {
            throw new InvalidArgumentException('Invalid mode specified');
        }

        $size = $media->getBox();

        $ratios = array(
            $settings['width'] / $size->getWidth(),
            $settings['height'] / $size->getHeight()
        );

        if ($this->mode === ImageInterface::THUMBNAIL_INSET) {
            $ratio = min($ratios);
        } else {
            $ratio = max($ratios);
        }

        return $size->scale($ratio);
    }
}
}
 



namespace Sonata\MediaBundle\Resizer
{

use Imagine\Image\ImagineInterface;
use Imagine\Image\Box;
use Imagine\Image\Point;
use Gaufrette\File;
use Sonata\MediaBundle\Model\MediaInterface;
use Sonata\MediaBundle\Metadata\MetadataBuilderInterface;


class SquareResizer implements ResizerInterface
{
    
    protected $adapter;

    
    protected $mode;

    
    public function __construct(ImagineInterface $adapter, $mode, MetadataBuilderInterface $metadata)
    {
        $this->adapter = $adapter;
        $this->mode    = $mode;
        $this->metadata = $metadata;
    }

    
    public function resize(MediaInterface $media, File $in, File $out, $format, array $settings)
    {
        if (!isset($settings['width'])) {
            throw new \RuntimeException(sprintf('Width parameter is missing in context "%s" for provider "%s"', $media->getContext(), $media->getProviderName()));
        }

        $image = $this->adapter->load($in->getContent());
        $size  = $media->getBox();

        if (null != $settings['height']) {
            if ($size->getHeight() > $size->getWidth()) {
                $higher = $size->getHeight();
                $lower  = $size->getWidth();
            } else {
                $higher = $size->getWidth();
                $lower  = $size->getHeight();
            }

            $crop = $higher - $lower;

            if ($crop > 0) {
                $point = $higher == $size->getHeight() ? new Point(0, 0) : new Point($crop / 2, 0);
                $image->crop($point, new Box($lower, $lower));
                $size = $image->getSize();
            }
        }

        $settings['height'] = (int) ($settings['width'] * $size->getHeight() / $size->getWidth());

        if ($settings['height'] < $size->getHeight() && $settings['width'] < $size->getWidth()) {
            $content = $image
                ->thumbnail(new Box($settings['width'], $settings['height']), $this->mode)
                ->get($format, array('quality' => $settings['quality']));
        } else {
            $content = $image->get($format, array('quality' => $settings['quality']));
        }

        $out->setContent($content, $this->metadata->get($media, $out->getName()));
    }

    
    public function getBox(MediaInterface $media, array $settings)
    {
        $size = $media->getBox();

        if (null != $settings['height']) {

            if ($size->getHeight() > $size->getWidth()) {
                $higher = $size->getHeight();
                $lower  = $size->getWidth();
            } else {
                $higher = $size->getWidth();
                $lower  = $size->getHeight();
            }

            if ($higher - $lower > 0) {
                return new Box($lower, $lower);
            }
        }

        $settings['height'] = (int) ($settings['width'] * $size->getHeight() / $size->getWidth());

        if ($settings['height'] < $size->getHeight() && $settings['width'] < $size->getWidth()) {
            return new Box($settings['width'], $settings['height']);
        }

        return $size;
    }
}
}
 



namespace Sonata\MediaBundle\Security
{

use Sonata\MediaBundle\Model\MediaInterface;
use Symfony\Component\HttpFoundation\Request;

interface DownloadStrategyInterface
{
    
    public function isGranted(MediaInterface $media, Request $request);

    
    public function getDescription();
}
}
 



namespace Sonata\MediaBundle\Security
{

use Sonata\MediaBundle\Model\MediaInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\TranslatorInterface;

class ForbiddenDownloadStrategy implements DownloadStrategyInterface
{
    protected $translator;

    
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    
    public function isGranted(MediaInterface $media, Request $request)
    {
        return false;
    }

    
    public function getDescription()
    {
        return $this->translator->trans('description.forbidden_download_strategy', array(), 'SonataMediaBundle');
    }
}
}
 



namespace Sonata\MediaBundle\Security
{

use Sonata\MediaBundle\Model\MediaInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\TranslatorInterface;

class PublicDownloadStrategy implements DownloadStrategyInterface
{
    protected $translator;

    
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    
    public function isGranted(MediaInterface $media, Request $request)
    {
        return true;
    }

    
    public function getDescription()
    {
        return $this->translator->trans('description.public_download_strategy', array(), 'SonataMediaBundle');
    }
}
}
 



namespace Sonata\MediaBundle\Security
{

use Sonata\MediaBundle\Model\MediaInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Translation\TranslatorInterface;

class RolesDownloadStrategy implements DownloadStrategyInterface
{
    protected $roles;

    protected $security;

    protected $translator;

    
    public function __construct(TranslatorInterface $translator, SecurityContextInterface $security, array $roles = array())
    {
        $this->roles      = $roles;
        $this->security   = $security;
        $this->translator = $translator;
    }

    
    public function isGranted(MediaInterface $media, Request $request)
    {
        return $this->security->getToken() && $this->security->isGranted($this->roles);
    }

    
    public function getDescription()
    {
        return $this->translator->trans('description.roles_download_strategy', array('%roles%' => implode(', ', $this->roles)), 'SonataMediaBundle');
    }
}
}
 



namespace Sonata\MediaBundle\Security
{

use Sonata\MediaBundle\Model\MediaInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SessionDownloadStrategy implements DownloadStrategyInterface
{
    protected $container;

    protected $translator;

    protected $times;

    protected $sessionKey = 'sonata/media/session/times';

    
    public function __construct(TranslatorInterface $translator, ContainerInterface $container, $times)
    {
        $this->times      = $times;
        $this->container  = $container;
        $this->translator = $translator;
    }

    
    public function isGranted(MediaInterface $media, Request $request)
    {
        if (!$this->container->has('session')) {
            return false;
        }

        $times = $this->getSession()->get($this->sessionKey, 0);

        if ($times >= $this->times) {
            return false;
        }

        $times++;

        $this->getSession()->set($this->sessionKey, $times);

        return true;
    }

    
    public function getDescription()
    {
        return $this->translator->trans('description.session_download_strategy', array('%times%' => $this->times), 'SonataMediaBundle');
    }

    
    private function getSession()
    {
        return $this->container->get('session');
    }
}
}
 



namespace Symfony\Component\Templating\Helper
{


interface HelperInterface
{
    
    public function getName();

    
    public function setCharset($charset);

    
    public function getCharset();
}
}
 



namespace Symfony\Component\Templating\Helper
{


abstract class Helper implements HelperInterface
{
    protected $charset = 'UTF-8';

    
    public function setCharset($charset)
    {
        $this->charset = $charset;
    }

    
    public function getCharset()
    {
        return $this->charset;
    }
}
}
 



namespace Sonata\MediaBundle\Templating\Helper
{

use Symfony\Component\Templating\Helper\Helper;
use Sonata\MediaBundle\Model\MediaInterface;
use Sonata\MediaBundle\Provider\MediaProviderInterface;
use Sonata\MediaBundle\Provider\Pool;
use Symfony\Component\Templating\EngineInterface;


class MediaHelper extends Helper
{
    protected $pool = null;

    protected $templating = null;

    
    public function __construct(Pool $pool, EngineInterface $templating)
    {
        $this->pool       = $pool;
        $this->templating = $templating;
    }

    
    public function getName()
    {
        return 'sonata_media';
    }

    
    public function media($media, $format, $options = array())
    {
        if (!$media) {
            return '';
        }

        $provider = $this->getProvider($media);

        $format = $provider->getFormatName($media, $format);

        $options = $provider->getHelperProperties($media, $format, $options);

        return $this->templating->render($provider->getTemplate('helper_view'), array(
             'media'    => $media,
             'format'   => $format,
             'options'  => $options,
        ));
    }

    
    private function getProvider(MediaInterface $media)
    {
        return $this->pool->getProvider($media->getProviderName());
    }

    
    public function thumbnail($media, $format, $options = array())
    {
         if (!$media) {
             return '';
         }

         $provider = $this->getProvider($media);

         $format = $provider->getFormatName($media, $format);
         $formatDefinition = $provider->getFormat($format);

                  $options = array_merge(array(
             'title' => $media->getName(),
             'width' => $formatDefinition['width'],
         ), $options);

         $options['src'] = $provider->generatePublicUrl($media, $format);

         return $this->getTemplating()->render($provider->getTemplate('helper_thumbnail'), array(
             'media'    => $media,
             'options'  => $options,
         ));
    }

    
    public function path($media, $format)
    {
        if (!$media) {
             return '';
        }

        $provider = $this->getProvider($media);

        $format = $provider->getFormatName($media, $format);

        return $provider->generatePublicUrl($media, $format);
    }
}
}
 



namespace Sonata\MediaBundle\Thumbnail
{

use Sonata\MediaBundle\Model\MediaInterface;
use Sonata\MediaBundle\Provider\MediaProviderInterface;

interface ThumbnailInterface
{
    
    public function generatePublicUrl(MediaProviderInterface $provider, MediaInterface $media, $format);

    
    public function generatePrivateUrl(MediaProviderInterface $provider, MediaInterface $media, $format);

    
    public function generate(MediaProviderInterface $provider, MediaInterface $media);

    
    public function delete(MediaProviderInterface $provider, MediaInterface $media);
}
}
 



namespace Sonata\MediaBundle\Thumbnail
{

use Sonata\MediaBundle\Model\MediaInterface;
use Sonata\MediaBundle\Provider\MediaProviderInterface;
use Sonata\NotificationBundle\Backend\BackendInterface;

class ConsumerThumbnail implements ThumbnailInterface
{
    protected $id;

    protected $thumbnail;

    protected $backend;

    
    public function __construct($id, ThumbnailInterface $thumbnail, BackendInterface $backend)
    {
        $this->id        = $id;
        $this->thumbnail = $thumbnail;
        $this->backend   = $backend;
    }

    
    public function generatePublicUrl(MediaProviderInterface $provider, MediaInterface $media, $format)
    {
        return $this->thumbnail->generatePrivateUrl($provider, $media, $format);
    }

    
    public function generatePrivateUrl(MediaProviderInterface $provider, MediaInterface $media, $format)
    {
        return $this->thumbnail->generatePrivateUrl($provider, $media, $format);
    }

    
    public function generate(MediaProviderInterface $provider, MediaInterface $media)
    {
        $this->backend->createAndPublish('sonata.media.create_thumbnail', array(
            'thumbnailId'       => $this->id,
            'mediaId'           => $media->getId(),

                                    'providerReference' => $media->getProviderReference(),
        ));
    }

    
    public function delete(MediaProviderInterface $provider, MediaInterface $media)
    {
        return $this->thumbnail->delete($provider, $media);
    }
}
}
 



namespace Sonata\MediaBundle\Thumbnail
{

use Sonata\MediaBundle\Model\MediaInterface;
use Sonata\MediaBundle\Provider\MediaProviderInterface;

class FormatThumbnail implements ThumbnailInterface
{
    private $defaultFormat;

    
    public function __construct($defaultFormat)
    {
        $this->defaultFormat = $defaultFormat;
    }

    
    public function generatePublicUrl(MediaProviderInterface $provider, MediaInterface $media, $format)
    {
        if ($format == 'reference') {
            $path = $provider->getReferenceImage($media);
        } else {
            $path = sprintf('%s/thumb_%s_%s.%s',  $provider->generatePath($media), $media->getId(), $format, $this->getExtension($media));
        }

        return $path;
    }

    
    public function generatePrivateUrl(MediaProviderInterface $provider, MediaInterface $media, $format)
    {
        return sprintf('%s/thumb_%s_%s.%s',
            $provider->generatePath($media),
            $media->getId(),
            $format,
            $this->getExtension($media)
        );
    }

    
    public function generate(MediaProviderInterface $provider, MediaInterface $media)
    {
        if (!$provider->requireThumbnails()) {
            return;
        }

        $referenceFile = $provider->getReferenceFile($media);

        foreach ($provider->getFormats() as $format => $settings) {
            if (substr($format, 0, strlen($media->getContext())) == $media->getContext() || $format === 'admin') {
                $provider->getResizer()->resize(
                    $media,
                    $referenceFile,
                    $provider->getFilesystem()->get($provider->generatePrivateUrl($media, $format), true),
                    $this->getExtension($media),
                    $settings
                );
            }
        }
    }

    
    public function delete(MediaProviderInterface $provider, MediaInterface $media)
    {
                foreach ($provider->getFormats() as $format => $definition) {
            $path = $provider->generatePrivateUrl($media, $format);
            if ($path && $provider->getFilesystem()->has($path)) {
                $provider->getFilesystem()->delete($path);
            }
        }
    }

    
    protected function getExtension(MediaInterface $media)
    {
        $ext = $media->getExtension();
        if (!is_string($ext) || strlen($ext) < 3) {
            $ext = $this->defaultFormat;
        }

        return $ext;
    }
}
}
 



namespace Sonata\MediaBundle\Twig\Extension
{

use Sonata\MediaBundle\Twig\TokenParser\MediaTokenParser;
use Sonata\MediaBundle\Twig\TokenParser\ThumbnailTokenParser;
use Sonata\MediaBundle\Twig\TokenParser\PathTokenParser;

use Sonata\MediaBundle\Model\MediaInterface;
use Sonata\MediaBundle\Model\MediaManagerInterface;
use Sonata\MediaBundle\Provider\Pool;

class MediaExtension extends \Twig_Extension
{
    protected $mediaService;

    protected $ressources = array();

    protected $mediaManager;

    protected $environment;

    
    public function __construct(Pool $mediaService, MediaManagerInterface $mediaManager)
    {
        $this->mediaService = $mediaService;
        $this->mediaManager = $mediaManager;
    }

    
    public function getTokenParsers()
    {
        return array(
            new MediaTokenParser($this->getName()),
            new ThumbnailTokenParser($this->getName()),
            new PathTokenParser($this->getName()),
        );
    }

    
    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    
    public function getName()
    {
        return 'sonata_media';
    }

    
    public function media($media = null, $format, $options = array())
    {
        $media = $this->getMedia($media);

        if (!$media) {
            return '';
        }

        $provider = $this
            ->getMediaService()
            ->getProvider($media->getProviderName());

        $format = $provider->getFormatName($media, $format);

        $options = $provider->getHelperProperties($media, $format, $options);

        return $this->render($provider->getTemplate('helper_view'), array(
            'media'    => $media,
            'format'   => $format,
            'options'  => $options,
        ));
    }

    
    private function getMedia($media)
    {
        if (!$media instanceof MediaInterface && strlen($media) > 0) {
            $media = $this->mediaManager->findOneBy(array(
                'id' => $media
            ));
        }

        if (!$media instanceof MediaInterface) {
            return false;
        }

        if ($media->getProviderStatus() !== MediaInterface::STATUS_OK) {
            return false;
        }

        return $media;
    }

    
    public function thumbnail($media = null, $format, $options = array())
    {
        $media = $this->getMedia($media);

        if (!$media) {
            return '';
        }

        $provider = $this->getMediaService()
           ->getProvider($media->getProviderName());

        $format = $provider->getFormatName($media, $format);
        $format_definition = $provider->getFormat($format);

                $defaultOptions = array(
            'title' => $media->getName(),
        );

        if ($format_definition['width']) {
            $defaultOptions['width'] = $format_definition['width'];
        }
        if ($format_definition['height']) {
            $defaultOptions['height'] = $format_definition['height'];
        }

        $options = array_merge($defaultOptions, $options);

        $options['src'] = $provider->generatePublicUrl($media, $format);

        return $this->render($provider->getTemplate('helper_thumbnail'), array(
            'media'    => $media,
            'options'  => $options,
        ));
    }

    
    public function render($template, array $parameters = array())
    {
        if (!isset($this->ressources[$template])) {
            $this->ressources[$template] = $this->environment->loadTemplate($template);
        }

        return $this->ressources[$template]->render($parameters);
    }

    
    public function path($media = null, $format)
    {
        $media = $this->getMedia($media);

        if (!$media) {
             return '';
        }

        $provider = $this->getMediaService()
           ->getProvider($media->getProviderName());

        $format = $provider->getFormatName($media, $format);

        return $provider->generatePublicUrl($media, $format);
    }

    
    public function getMediaService()
    {
        return $this->mediaService;
    }
}
}

namespace
{

/*
 * This file is part of Twig.
 *
 * (c) 2010 Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
/**
 * Represents a node in the AST.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @deprecated since 1.12 (to be removed in 2.0)
 */
interface Twig_NodeInterface extends Countable, IteratorAggregate
{
    /**
     * Compiles the node to PHP.
     *
     * @param Twig_Compiler A Twig_Compiler instance
     */
    public function compile(Twig_Compiler $compiler);
    public function getLine();
    public function getNodeTag();
}

}

namespace
{

/*
 * This file is part of Twig.
 *
 * (c) 2009 Fabien Potencier
 * (c) 2009 Armin Ronacher
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
/**
 * Represents a node in the AST.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class Twig_Node implements Twig_NodeInterface
{
    protected $nodes;
    protected $attributes;
    protected $lineno;
    protected $tag;
    /**
     * Constructor.
     *
     * The nodes are automatically made available as properties ($this->node).
     * The attributes are automatically made available as array items ($this['name']).
     *
     * @param array   $nodes      An array of named nodes
     * @param array   $attributes An array of attributes (should not be nodes)
     * @param integer $lineno     The line number
     * @param string  $tag        The tag name associated with the Node
     */
    public function __construct(array $nodes = array(), array $attributes = array(), $lineno = 0, $tag = null)
    {
        $this->nodes = $nodes;
        $this->attributes = $attributes;
        $this->lineno = $lineno;
        $this->tag = $tag;
    }
    public function __toString()
    {
        $attributes = array();
        foreach ($this->attributes as $name => $value) {
            $attributes[] = sprintf('%s: %s', $name, str_replace("\n", '', var_export($value, true)));
        }
        $repr = array(get_class($this).'('.implode(', ', $attributes));
        if (count($this->nodes)) {
            foreach ($this->nodes as $name => $node) {
                $len = strlen($name) + 4;
                $noderepr = array();
                foreach (explode("\n", (string) $node) as $line) {
                    $noderepr[] = str_repeat(' ', $len).$line;
                }
                $repr[] = sprintf('  %s: %s', $name, ltrim(implode("\n", $noderepr)));
            }
            $repr[] = ')';
        } else {
            $repr[0] .= ')';
        }
        return implode("\n", $repr);
    }
    public function toXml($asDom = false)
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;
        $dom->appendChild($xml = $dom->createElement('twig'));
        $xml->appendChild($node = $dom->createElement('node'));
        $node->setAttribute('class', get_class($this));
        foreach ($this->attributes as $name => $value) {
            $node->appendChild($attribute = $dom->createElement('attribute'));
            $attribute->setAttribute('name', $name);
            $attribute->appendChild($dom->createTextNode($value));
        }
        foreach ($this->nodes as $name => $n) {
            if (null === $n) {
                continue;
            }
            $child = $n->toXml(true)->getElementsByTagName('node')->item(0);
            $child = $dom->importNode($child, true);
            $child->setAttribute('name', $name);
            $node->appendChild($child);
        }
        return $asDom ? $dom : $dom->saveXml();
    }
    public function compile(Twig_Compiler $compiler)
    {
        foreach ($this->nodes as $node) {
            $node->compile($compiler);
        }
    }
    public function getLine()
    {
        return $this->lineno;
    }
    public function getNodeTag()
    {
        return $this->tag;
    }
    /**
     * Returns true if the attribute is defined.
     *
     * @param  string  The attribute name
     *
     * @return Boolean true if the attribute is defined, false otherwise
     */
    public function hasAttribute($name)
    {
        return array_key_exists($name, $this->attributes);
    }
    /**
     * Gets an attribute.
     *
     * @param  string The attribute name
     *
     * @return mixed The attribute value
     */
    public function getAttribute($name)
    {
        if (!array_key_exists($name, $this->attributes)) {
            throw new LogicException(sprintf('Attribute "%s" does not exist for Node "%s".', $name, get_class($this)));
        }
        return $this->attributes[$name];
    }
    /**
     * Sets an attribute.
     *
     * @param string The attribute name
     * @param mixed  The attribute value
     */
    public function setAttribute($name, $value)
    {
        $this->attributes[$name] = $value;
    }
    /**
     * Removes an attribute.
     *
     * @param string The attribute name
     */
    public function removeAttribute($name)
    {
        unset($this->attributes[$name]);
    }
    /**
     * Returns true if the node with the given identifier exists.
     *
     * @param  string  The node name
     *
     * @return Boolean true if the node with the given name exists, false otherwise
     */
    public function hasNode($name)
    {
        return array_key_exists($name, $this->nodes);
    }
    /**
     * Gets a node by name.
     *
     * @param  string The node name
     *
     * @return Twig_Node A Twig_Node instance
     */
    public function getNode($name)
    {
        if (!array_key_exists($name, $this->nodes)) {
            throw new LogicException(sprintf('Node "%s" does not exist for Node "%s".', $name, get_class($this)));
        }
        return $this->nodes[$name];
    }
    /**
     * Sets a node.
     *
     * @param string    The node name
     * @param Twig_Node A Twig_Node instance
     */
    public function setNode($name, $node = null)
    {
        $this->nodes[$name] = $node;
    }
    /**
     * Removes a node by name.
     *
     * @param string The node name
     */
    public function removeNode($name)
    {
        unset($this->nodes[$name]);
    }
    public function count()
    {
        return count($this->nodes);
    }
    public function getIterator()
    {
        return new ArrayIterator($this->nodes);
    }
}

}
 



namespace Sonata\MediaBundle\Twig\Node
{

class MediaNode extends \Twig_Node
{
    protected $extensionName;

    
    public function __construct($extensionName, \Twig_Node_Expression $media, \Twig_Node_Expression $format, \Twig_Node_Expression $attributes, $lineno, $tag = null)
    {
        $this->extensionName = $extensionName;

        parent::__construct(array('media' => $media, 'format' => $format,'attributes' => $attributes), array(), $lineno, $tag);
    }

    
    public function compile(\Twig_Compiler $compiler)
    {
        $compiler
            ->addDebugInfo($this)
            ->write(sprintf("echo \$this->env->getExtension('%s')->media(", $this->extensionName))
            ->subcompile($this->getNode('media'))
            ->raw(', ')
            ->subcompile($this->getNode('format'))
            ->raw(', ')
            ->subcompile($this->getNode('attributes'))
            ->raw(");\n")
        ;
    }
}
}
 



namespace Sonata\MediaBundle\Twig\Node
{

class PathNode extends \Twig_Node
{
    protected $extensionName;

    
    public function __construct($extensionName, \Twig_Node_Expression $media, \Twig_Node_Expression $format, $lineno, $tag = null)
    {
        $this->extensionName = $extensionName;

        parent::__construct(array('media' => $media, 'format' => $format), array(), $lineno, $tag);
    }

    
    public function compile(\Twig_Compiler $compiler)
    {
        $compiler
            ->addDebugInfo($this)
            ->write(sprintf("echo \$this->env->getExtension('%s')->path(", $this->extensionName))
            ->subcompile($this->getNode('media'))
            ->raw(', ')
            ->subcompile($this->getNode('format'))
            ->raw(");\n")
        ;
    }
}
}
 



namespace Sonata\MediaBundle\Twig\Node
{

class ThumbnailNode extends \Twig_Node
{
    protected $extensionName;

    
    public function __construct($extensionName, \Twig_Node_Expression $media, \Twig_Node_Expression $format, \Twig_Node_Expression $attributes, $lineno, $tag = null)
    {
        $this->extensionName = $extensionName;

        parent::__construct(array('media' => $media, 'format' => $format,'attributes' => $attributes), array(), $lineno, $tag);
    }

    
    public function compile(\Twig_Compiler $compiler)
    {
        $compiler
            ->addDebugInfo($this)
            ->write(sprintf("echo \$this->env->getExtension('%s')->thumbnail(", $this->extensionName))
            ->subcompile($this->getNode('media'))
            ->raw(', ')
            ->subcompile($this->getNode('format'))
            ->raw(', ')
            ->subcompile($this->getNode('attributes'))
            ->raw(");\n")
        ;
    }
}
}
 



namespace Symfony\Component\HttpKernel\Log
{


interface DebugLoggerInterface
{
    
    public function getLogs();

    
    public function countErrors();
}
}
 



namespace Symfony\Bridge\Monolog\Handler
{

use Monolog\Logger;
use Monolog\Handler\TestHandler;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;


class DebugHandler extends TestHandler implements DebugLoggerInterface
{
    
    public function getLogs()
    {
        $records = array();
        foreach ($this->records as $record) {
            $records[] = array(
                'timestamp'    => $record['datetime']->getTimestamp(),
                'message'      => $record['message'],
                'priority'     => $record['level'],
                'priorityName' => $record['level_name'],
                'context'      => $record['context'],
            );
        }

        return $records;
    }

    
    public function countErrors()
    {
        $cnt = 0;
        $levels = array(Logger::ERROR, Logger::CRITICAL, Logger::ALERT);
        if (defined('Monolog\Logger::EMERGENCY')) {
            $levels[] = Logger::EMERGENCY;
        }
        foreach ($levels as $level) {
            if (isset($this->recordsByLevel[$level])) {
                $cnt += count($this->recordsByLevel[$level]);
            }
        }

        return $cnt;
    }
}
}
 



namespace Symfony\Component\HttpKernel\Log
{


interface LoggerInterface
{
    
    public function emerg($message, array $context = array());

    
    public function alert($message, array $context = array());

    
    public function crit($message, array $context = array());

    
    public function err($message, array $context = array());

    
    public function warn($message, array $context = array());

    
    public function notice($message, array $context = array());

    
    public function info($message, array $context = array());

    
    public function debug($message, array $context = array());
}
}
 



namespace Symfony\Bridge\Monolog
{

use Monolog\Logger as BaseLogger;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;


class Logger extends BaseLogger implements LoggerInterface, DebugLoggerInterface
{
    
    public function getLogs()
    {
        if ($logger = $this->getDebugLogger()) {
            return $logger->getLogs();
        }
    }

    
    public function countErrors()
    {
        if ($logger = $this->getDebugLogger()) {
            return $logger->countErrors();
        }
    }

    
    private function getDebugLogger()
    {
        foreach ($this->handlers as $handler) {
            if ($handler instanceof DebugLoggerInterface) {
                return $handler;
            }
        }
    }
}
}
 



namespace Assetic
{


interface ValueSupplierInterface
{
    
    public function getValues();
}
}
 

namespace Symfony\Bundle\AsseticBundle
{

use Assetic\ValueSupplierInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class DefaultValueSupplier implements ValueSupplierInterface
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getValues()
    {
        if (!$this->container->isScopeActive('request')) {
            return array();
        }

        $request = $this->container->get('request');

        return array(
            'locale' => $request->getLocale(),
            'env'    => $this->container->getParameter('kernel.environment'),
        );
    }
}}
 



namespace Assetic\Factory
{

use Assetic\Asset\AssetCollection;
use Assetic\Asset\AssetCollectionInterface;
use Assetic\Asset\AssetInterface;
use Assetic\Asset\AssetReference;
use Assetic\Asset\FileAsset;
use Assetic\Asset\GlobAsset;
use Assetic\Asset\HttpAsset;
use Assetic\AssetManager;
use Assetic\Factory\Worker\WorkerInterface;
use Assetic\FilterManager;


class AssetFactory
{
    private $root;
    private $debug;
    private $output;
    private $workers;
    private $am;
    private $fm;

    
    public function __construct($root, $debug = false)
    {
        $this->root      = rtrim($root, '/');
        $this->debug     = $debug;
        $this->output    = 'assetic/*';
        $this->workers   = array();
    }

    
    public function setDebug($debug)
    {
        $this->debug = $debug;
    }

    
    public function isDebug()
    {
        return $this->debug;
    }

    
    public function setDefaultOutput($output)
    {
        $this->output = $output;
    }

    
    public function addWorker(WorkerInterface $worker)
    {
        $this->workers[] = $worker;
    }

    
    public function getAssetManager()
    {
        return $this->am;
    }

    
    public function setAssetManager(AssetManager $am)
    {
        $this->am = $am;
    }

    
    public function getFilterManager()
    {
        return $this->fm;
    }

    
    public function setFilterManager(FilterManager $fm)
    {
        $this->fm = $fm;
    }

    
    public function createAsset($inputs = array(), $filters = array(), array $options = array())
    {
        if (!is_array($inputs)) {
            $inputs = array($inputs);
        }

        if (!is_array($filters)) {
            $filters = array($filters);
        }

        if (!isset($options['output'])) {
            $options['output'] = $this->output;
        }

        if (!isset($options['vars'])) {
            $options['vars'] = array();
        }

        if (!isset($options['debug'])) {
            $options['debug'] = $this->debug;
        }

        if (!isset($options['root'])) {
            $options['root'] = array($this->root);
        } else {
            if (!is_array($options['root'])) {
                $options['root'] = array($options['root']);
            }

            $options['root'][] = $this->root;
        }

        if (!isset($options['name'])) {
            $options['name'] = $this->generateAssetName($inputs, $filters, $options);
        }

        $asset = $this->createAssetCollection(array(), $options);
        $extensions = array();

                foreach ($inputs as $input) {
            if (is_array($input)) {
                                $asset->add(call_user_func_array(array($this, 'createAsset'), $input));
            } else {
                $asset->add($this->parseInput($input, $options));
                $extensions[pathinfo($input, PATHINFO_EXTENSION)] = true;
            }
        }

                foreach ($filters as $filter) {
            if ('?' != $filter[0]) {
                $asset->ensureFilter($this->getFilter($filter));
            } elseif (!$options['debug']) {
                $asset->ensureFilter($this->getFilter(substr($filter, 1)));
            }
        }

                if (!empty($options['vars'])) {
            $toAdd = array();
            foreach ($options['vars'] as $var) {
                if (false !== strpos($options['output'], '{'.$var.'}')) {
                    continue;
                }

                $toAdd[] = '{'.$var.'}';
            }

            if ($toAdd) {
                $options['output'] = str_replace('*', '*.'.implode('.', $toAdd), $options['output']);
            }
        }

                if (1 == count($extensions) && !pathinfo($options['output'], PATHINFO_EXTENSION) && $extension = key($extensions)) {
            $options['output'] .= '.'.$extension;
        }

                $asset->setTargetPath(str_replace('*', $options['name'], $options['output']));

                return $this->applyWorkers($asset);
    }

    public function generateAssetName($inputs, $filters, $options = array())
    {
        foreach (array_diff(array_keys($options), array('output', 'debug', 'root')) as $key) {
            unset($options[$key]);
        }

        ksort($options);

        return substr(sha1(serialize($inputs).serialize($filters).serialize($options)), 0, 7);
    }

    
    protected function parseInput($input, array $options = array())
    {
        if ('@' == $input[0]) {
            return $this->createAssetReference(substr($input, 1));
        }

        if (false !== strpos($input, '://') || 0 === strpos($input, '//')) {
            return $this->createHttpAsset($input, $options['vars']);
        }

        if (self::isAbsolutePath($input)) {
            if ($root = self::findRootDir($input, $options['root'])) {
                $path = ltrim(substr($input, strlen($root)), '/');
            } else {
                $path = null;
            }
        } else {
            $root  = $this->root;
            $path  = $input;
            $input = $this->root.'/'.$path;
        }

        if (false !== strpos($input, '*')) {
            return $this->createGlobAsset($input, $root, $options['vars']);
        }

        return $this->createFileAsset($input, $root, $path, $options['vars']);
    }

    protected function createAssetCollection(array $assets = array(), array $options = array())
    {
        return new AssetCollection($assets, array(), null, isset($options['vars']) ? $options['vars'] : array());
    }

    protected function createAssetReference($name)
    {
        if (!$this->am) {
            throw new \LogicException('There is no asset manager.');
        }

        return new AssetReference($this->am, $name);
    }

    protected function createHttpAsset($sourceUrl, $vars)
    {
        return new HttpAsset($sourceUrl, array(), false, $vars);
    }

    protected function createGlobAsset($glob, $root = null, $vars)
    {
        return new GlobAsset($glob, array(), $root, $vars);
    }

    protected function createFileAsset($source, $root = null, $path = null, $vars)
    {
        return new FileAsset($source, array(), $root, $path, $vars);
    }

    protected function getFilter($name)
    {
        if (!$this->fm) {
            throw new \LogicException('There is no filter manager.');
        }

        return $this->fm->get($name);
    }

    
    private function applyWorkers(AssetCollectionInterface $asset)
    {
        foreach ($asset as $leaf) {
            foreach ($this->workers as $worker) {
                $retval = $worker->process($leaf);

                if ($retval instanceof AssetInterface && $leaf !== $retval) {
                    $asset->replaceLeaf($leaf, $retval);
                }
            }
        }

        foreach ($this->workers as $worker) {
            $retval = $worker->process($asset);

            if ($retval instanceof AssetInterface) {
                $asset = $retval;
            }
        }

        return $asset instanceof AssetCollectionInterface ? $asset : $this->createAssetCollection(array($asset));
    }

    private static function isAbsolutePath($path)
    {
        return '/' == $path[0] || '\\' == $path[0] || (3 < strlen($path) && ctype_alpha($path[0]) && $path[1] == ':' && ('\\' == $path[2] || '/' == $path[2]));
    }

    
    private static function findRootDir($path, array $roots)
    {
        foreach ($roots as $root) {
            if (0 === strpos($path, $root)) {
                return $root;
            }
        }
    }
}
}
 



namespace Symfony\Bundle\AsseticBundle\Factory
{

use Assetic\Factory\AssetFactory as BaseAssetFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\KernelInterface;


class AssetFactory extends BaseAssetFactory
{
    private $kernel;
    private $container;
    private $parameterBag;

    
    public function __construct(KernelInterface $kernel, ContainerInterface $container, ParameterBagInterface $parameterBag, $baseDir, $debug = false)
    {
        $this->kernel = $kernel;
        $this->container = $container;
        $this->parameterBag = $parameterBag;

        parent::__construct($baseDir, $debug);
    }

    
    protected function parseInput($input, array $options = array())
    {
        $input = $this->parameterBag->resolveValue($input);

                if ('@' == $input[0] && false !== strpos($input, '/')) {
                        $bundle = substr($input, 1);
            if (false !== $pos = strpos($bundle, '/')) {
                $bundle = substr($bundle, 0, $pos);
            }
            $options['root'] = array($this->kernel->getBundle($bundle)->getPath());

                        if (false !== $pos = strpos($input, '*')) {
                                list($before, $after) = explode('*', $input, 2);
                $input = $this->kernel->locateResource($before).'*'.$after;
            } else {
                $input = $this->kernel->locateResource($input);
            }
        }

        return parent::parseInput($input, $options);
    }

    protected function createAssetReference($name)
    {
        if (!$this->getAssetManager()) {
            $this->setAssetManager($this->container->get('assetic.asset_manager'));
        }

        return parent::createAssetReference($name);
    }

    protected function getFilter($name)
    {
        if (!$this->getFilterManager()) {
            $this->setFilterManager($this->container->get('assetic.filter_manager'));
        }

        return parent::getFilter($name);
    }
}
}
 



namespace Symfony\Bundle\FrameworkBundle\Controller
{

use Symfony\Component\HttpKernel\KernelInterface;


class ControllerNameParser
{
    protected $kernel;

    
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    
    public function parse($controller)
    {
        if (3 != count($parts = explode(':', $controller))) {
            throw new \InvalidArgumentException(sprintf('The "%s" controller is not a valid a:b:c controller string.', $controller));
        }

        list($bundle, $controller, $action) = $parts;
        $controller = str_replace('/', '\\', $controller);
        $class = null;
        $logs = array();
        foreach ($this->kernel->getBundle($bundle, false) as $b) {
            $try = $b->getNamespace().'\\Controller\\'.$controller.'Controller';
            if (!class_exists($try)) {
                $logs[] = sprintf('Unable to find controller "%s:%s" - class "%s" does not exist.', $bundle, $controller, $try);
            } else {
                $class = $try;

                break;
            }
        }

        if (null === $class) {
            $this->handleControllerNotFoundException($bundle, $controller, $logs);
        }

        return $class.'::'.$action.'Action';
    }

    private function handleControllerNotFoundException($bundle, $controller, array $logs)
    {
                if (1 == count($logs)) {
            throw new \InvalidArgumentException($logs[0]);
        }

                $names = array();
        foreach ($this->kernel->getBundle($bundle, false) as $b) {
            $names[] = $b->getName();
        }
        $msg = sprintf('Unable to find controller "%s:%s" in bundles %s.', $bundle, $controller, implode(', ', $names));

        throw new \InvalidArgumentException($msg);
    }
}
}
 



namespace Symfony\Bundle\FrameworkBundle\EventListener
{

use Symfony\Component\DependencyInjection\ContainerInterface;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


class SessionListener implements EventSubscriberInterface
{
    
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        $request = $event->getRequest();
        if (!$this->container->has('session') || $request->hasSession()) {
            return;
        }

        $request->setSession($this->container->get('session'));
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => array('onKernelRequest', 128),
        );
    }
}
}
 



namespace Symfony\Component\Routing\Matcher
{


interface RedirectableUrlMatcherInterface
{
    
    public function redirect($path, $route, $scheme = null);
}
}
 



namespace Symfony\Component\Routing
{


interface RequestContextAwareInterface
{
    
    public function setContext(RequestContext $context);

    
    public function getContext();
}
}
 



namespace Symfony\Component\Routing\Matcher
{

use Symfony\Component\Routing\RequestContextAwareInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;


interface UrlMatcherInterface extends RequestContextAwareInterface
{
    
    public function match($pathinfo);
}
}
 



namespace Symfony\Component\Routing\Matcher
{

use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;


class UrlMatcher implements UrlMatcherInterface
{
    const REQUIREMENT_MATCH     = 0;
    const REQUIREMENT_MISMATCH  = 1;
    const ROUTE_MATCH           = 2;

    protected $context;
    protected $allow;

    private $routes;

    
    public function __construct(RouteCollection $routes, RequestContext $context)
    {
        $this->routes = $routes;
        $this->context = $context;
    }

    
    public function setContext(RequestContext $context)
    {
        $this->context = $context;
    }

    
    public function getContext()
    {
        return $this->context;
    }

    
    public function match($pathinfo)
    {
        $this->allow = array();

        if ($ret = $this->matchCollection(rawurldecode($pathinfo), $this->routes)) {
            return $ret;
        }

        throw 0 < count($this->allow)
            ? new MethodNotAllowedException(array_unique(array_map('strtoupper', $this->allow)))
            : new ResourceNotFoundException();
    }

    
    protected function matchCollection($pathinfo, RouteCollection $routes)
    {
        foreach ($routes as $name => $route) {
            if ($route instanceof RouteCollection) {
                if (false === strpos($route->getPrefix(), '{') && $route->getPrefix() !== substr($pathinfo, 0, strlen($route->getPrefix()))) {
                    continue;
                }

                if (!$ret = $this->matchCollection($pathinfo, $route)) {
                    continue;
                }

                return $ret;
            }

            $compiledRoute = $route->compile();

                        if ('' !== $compiledRoute->getStaticPrefix() && 0 !== strpos($pathinfo, $compiledRoute->getStaticPrefix())) {
                continue;
            }

            if (!preg_match($compiledRoute->getRegex(), $pathinfo, $matches)) {
                continue;
            }

                        if ($req = $route->getRequirement('_method')) {
                                if ('HEAD' === $method = $this->context->getMethod()) {
                    $method = 'GET';
                }

                if (!in_array($method, $req = explode('|', strtoupper($req)))) {
                    $this->allow = array_merge($this->allow, $req);

                    continue;
                }
            }

            $status = $this->handleRouteRequirements($pathinfo, $name, $route);

            if (self::ROUTE_MATCH === $status[0]) {
                return $status[1];
            }

            if (self::REQUIREMENT_MISMATCH === $status[0]) {
                continue;
            }

            return array_merge($this->mergeDefaults($matches, $route->getDefaults()), array('_route' => $name));
        }
    }

    
    protected function handleRouteRequirements($pathinfo, $name, Route $route)
    {
                $scheme = $route->getRequirement('_scheme');
        $status = $scheme && $scheme !== $this->context->getScheme() ? self::REQUIREMENT_MISMATCH : self::REQUIREMENT_MATCH;

        return array($status, null);
    }

    
    protected function mergeDefaults($params, $defaults)
    {
        $parameters = $defaults;
        foreach ($params as $key => $value) {
            if (!is_int($key)) {
                $parameters[$key] = $value;
            }
        }

        return $parameters;
    }
}
}
 



namespace Symfony\Component\Routing\Matcher
{

use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Route;


abstract class RedirectableUrlMatcher extends UrlMatcher implements RedirectableUrlMatcherInterface
{
    
    public function match($pathinfo)
    {
        try {
            $parameters = parent::match($pathinfo);
        } catch (ResourceNotFoundException $e) {
            if ('/' === substr($pathinfo, -1) || !in_array($this->context->getMethod(), array('HEAD', 'GET'))) {
                throw $e;
            }

            try {
                parent::match($pathinfo.'/');

                return $this->redirect($pathinfo.'/', null);
            } catch (ResourceNotFoundException $e2) {
                throw $e;
            }
        }

        return $parameters;
    }

    
    protected function handleRouteRequirements($pathinfo, $name, Route $route)
    {
                $scheme = $route->getRequirement('_scheme');
        if ($scheme && $this->context->getScheme() !== $scheme) {
            return array(self::ROUTE_MATCH, $this->redirect($pathinfo, $name, $scheme));
        }

        return array(self::REQUIREMENT_MATCH, null);
    }
}
}
 



namespace Symfony\Bundle\FrameworkBundle\Routing
{

use Symfony\Component\Routing\Matcher\RedirectableUrlMatcher as BaseMatcher;


class RedirectableUrlMatcher extends BaseMatcher
{
    
    public function redirect($path, $route, $scheme = null)
    {
        return array(
            '_controller' => 'Symfony\\Bundle\\FrameworkBundle\\Controller\\RedirectController::urlRedirectAction',
            'path'        => $path,
            'permanent'   => true,
            'scheme'      => $scheme,
            'httpPort'    => $this->context->getHttpPort(),
            'httpsPort'   => $this->context->getHttpsPort(),
            '_route'      => $route,
        );
    }
}
}
 



namespace Symfony\Component\Routing\Generator
{

use Symfony\Component\Routing\RequestContextAwareInterface;
use Symfony\Component\Routing\Exception\RouteNotFoundException;


interface UrlGeneratorInterface extends RequestContextAwareInterface
{
    
    public function generate($name, $parameters = array(), $absolute = false);
}
}
 



namespace Symfony\Component\Routing
{

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;


interface RouterInterface extends UrlMatcherInterface, UrlGeneratorInterface
{
    
    public function getRouteCollection();
}
}
 



namespace Symfony\Component\HttpKernel\CacheWarmer
{


interface WarmableInterface
{
    
    public function warmUp($cacheDir);
}
}
 



namespace Symfony\Component\Routing
{

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Symfony\Component\Routing\Generator\ConfigurableRequirementsInterface;


class Router implements RouterInterface
{
    protected $matcher;
    protected $generator;
    protected $context;
    protected $loader;
    protected $collection;
    protected $resource;
    protected $options;
    protected $logger;

    
    public function __construct(LoaderInterface $loader, $resource, array $options = array(), RequestContext $context = null, LoggerInterface $logger = null)
    {
        $this->loader = $loader;
        $this->resource = $resource;
        $this->logger = $logger;
        $this->context = null === $context ? new RequestContext() : $context;
        $this->setOptions($options);
    }

    
    public function setOptions(array $options)
    {
        $this->options = array(
            'cache_dir'              => null,
            'debug'                  => false,
            'generator_class'        => 'Symfony\\Component\\Routing\\Generator\\UrlGenerator',
            'generator_base_class'   => 'Symfony\\Component\\Routing\\Generator\\UrlGenerator',
            'generator_dumper_class' => 'Symfony\\Component\\Routing\\Generator\\Dumper\\PhpGeneratorDumper',
            'generator_cache_class'  => 'ProjectUrlGenerator',
            'matcher_class'          => 'Symfony\\Component\\Routing\\Matcher\\UrlMatcher',
            'matcher_base_class'     => 'Symfony\\Component\\Routing\\Matcher\\UrlMatcher',
            'matcher_dumper_class'   => 'Symfony\\Component\\Routing\\Matcher\\Dumper\\PhpMatcherDumper',
            'matcher_cache_class'    => 'ProjectUrlMatcher',
            'resource_type'          => null,
            'strict_requirements'    => true,
        );

                $invalid = array();
        foreach ($options as $key => $value) {
            if (array_key_exists($key, $this->options)) {
                $this->options[$key] = $value;
            } else {
                $invalid[] = $key;
            }
        }

        if ($invalid) {
            throw new \InvalidArgumentException(sprintf('The Router does not support the following options: "%s".', implode('\', \'', $invalid)));
        }
    }

    
    public function setOption($key, $value)
    {
        if (!array_key_exists($key, $this->options)) {
            throw new \InvalidArgumentException(sprintf('The Router does not support the "%s" option.', $key));
        }

        $this->options[$key] = $value;
    }

    
    public function getOption($key)
    {
        if (!array_key_exists($key, $this->options)) {
            throw new \InvalidArgumentException(sprintf('The Router does not support the "%s" option.', $key));
        }

        return $this->options[$key];
    }

    
    public function getRouteCollection()
    {
        if (null === $this->collection) {
            $this->collection = $this->loader->load($this->resource, $this->options['resource_type']);
        }

        return $this->collection;
    }

    
    public function setContext(RequestContext $context)
    {
        $this->context = $context;

        $this->getMatcher()->setContext($context);
        $this->getGenerator()->setContext($context);
    }

    
    public function getContext()
    {
        return $this->context;
    }

    
    public function generate($name, $parameters = array(), $absolute = false)
    {
        return $this->getGenerator()->generate($name, $parameters, $absolute);
    }

    
    public function match($pathinfo)
    {
        return $this->getMatcher()->match($pathinfo);
    }

    
    public function getMatcher()
    {
        if (null !== $this->matcher) {
            return $this->matcher;
        }

        if (null === $this->options['cache_dir'] || null === $this->options['matcher_cache_class']) {
            return $this->matcher = new $this->options['matcher_class']($this->getRouteCollection(), $this->context);
        }

        $class = $this->options['matcher_cache_class'];
        $cache = new ConfigCache($this->options['cache_dir'].'/'.$class.'.php', $this->options['debug']);
        if (!$cache->isFresh($class)) {
            $dumper = new $this->options['matcher_dumper_class']($this->getRouteCollection());

            $options = array(
                'class'      => $class,
                'base_class' => $this->options['matcher_base_class'],
            );

            $cache->write($dumper->dump($options), $this->getRouteCollection()->getResources());
        }

        require_once $cache;

        return $this->matcher = new $class($this->context);
    }

    
    public function getGenerator()
    {
        if (null !== $this->generator) {
            return $this->generator;
        }

        if (null === $this->options['cache_dir'] || null === $this->options['generator_cache_class']) {
            $this->generator = new $this->options['generator_class']($this->getRouteCollection(), $this->context, $this->logger);
        } else {
            $class = $this->options['generator_cache_class'];
            $cache = new ConfigCache($this->options['cache_dir'].'/'.$class.'.php', $this->options['debug']);
            if (!$cache->isFresh($class)) {
                $dumper = new $this->options['generator_dumper_class']($this->getRouteCollection());

                $options = array(
                    'class'      => $class,
                    'base_class' => $this->options['generator_base_class'],
                );

                $cache->write($dumper->dump($options), $this->getRouteCollection()->getResources());
            }

            require_once $cache;

            $this->generator = new $class($this->context, $this->logger);
        }

        if ($this->generator instanceof ConfigurableRequirementsInterface) {
            $this->generator->setStrictRequirements($this->options['strict_requirements']);
        }

        return $this->generator;
    }
}
}
 



namespace Symfony\Bundle\FrameworkBundle\Routing
{

use Symfony\Component\Routing\Router as BaseRouter;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\HttpKernel\CacheWarmer\WarmableInterface;
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;


class Router extends BaseRouter implements WarmableInterface
{
    private $container;

    
    public function __construct(ContainerInterface $container, $resource, array $options = array(), RequestContext $context = null)
    {
        $this->container = $container;

        $this->resource = $resource;
        $this->context = null === $context ? new RequestContext() : $context;
        $this->setOptions($options);
    }

    
    public function getRouteCollection()
    {
        if (null === $this->collection) {
            $this->collection = $this->container->get('routing.loader')->load($this->resource, $this->options['resource_type']);
            $this->resolveParameters($this->collection);
        }

        return $this->collection;
    }

    
    public function warmUp($cacheDir)
    {
        $currentDir = $this->getOption('cache_dir');

                $this->setOption('cache_dir', $cacheDir);
        $this->getMatcher();
        $this->getGenerator();

        $this->setOption('cache_dir', $currentDir);
    }

    
    private function resolveParameters(RouteCollection $collection)
    {
        foreach ($collection as $route) {
            if ($route instanceof RouteCollection) {
                $this->resolveParameters($route);
            } else {
                foreach ($route->getDefaults() as $name => $value) {
                    $route->setDefault($name, $this->resolveString($value));
                }

                foreach ($route->getRequirements() as $name => $value) {
                     $route->setRequirement($name, $this->resolveString($value));
                }

                $route->setPattern($this->resolveString($route->getPattern()));
            }
        }
    }

    
    private function resolveString($value)
    {
        $container = $this->container;

        if (null === $value || false === $value || true === $value || is_object($value)) {
            return $value;
        }

        $escapedValue = preg_replace_callback('/%%|%([^%\s]+)%/', function ($match) use ($container, $value) {
                        if (!isset($match[1])) {
                return '%%';
            }

            $key = strtolower($match[1]);

            if (!$container->hasParameter($key)) {
                throw new ParameterNotFoundException($key);
            }

            $resolved = $container->getParameter($key);

            if (is_string($resolved) || is_numeric($resolved)) {
                return (string) $resolved;
            }

            throw new RuntimeException(sprintf(
                'A string value must be composed of strings and/or numbers,' .
                'but found parameter "%s" of type %s inside string value "%s".',
                $key,
                gettype($resolved),
                $value)
            );

        }, $value);

        return str_replace('%%', '%', $escapedValue);
    }
}
}
 



namespace Symfony\Bundle\FrameworkBundle\Templating
{

use Symfony\Component\DependencyInjection\ContainerInterface;


class GlobalVariables
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    
    public function getSecurity()
    {
        if ($this->container->has('security.context')) {
            return $this->container->get('security.context');
        }
    }

    
    public function getUser()
    {
        if (!$security = $this->getSecurity()) {
            return;
        }

        if (!$token = $security->getToken()) {
            return;
        }

        $user = $token->getUser();
        if (!is_object($user)) {
            return;
        }

        return $user;
    }

    
    public function getRequest()
    {
        if ($this->container->has('request') && $request = $this->container->get('request')) {
            return $request;
        }
    }

    
    public function getSession()
    {
        if ($request = $this->getRequest()) {
            return $request->getSession();
        }
    }

    
    public function getEnvironment()
    {
        return $this->container->getParameter('kernel.environment');
    }

    
    public function getDebug()
    {
        return (Boolean) $this->container->getParameter('kernel.debug');
    }
}
}
 



namespace Symfony\Component\Config
{


interface FileLocatorInterface
{
    
    public function locate($name, $currentPath = null, $first = true);
}
}
 



namespace Symfony\Bundle\FrameworkBundle\Templating\Loader
{

use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\Templating\TemplateReferenceInterface;


class TemplateLocator implements FileLocatorInterface
{
    protected $locator;
    protected $cache;

    
    public function __construct(FileLocatorInterface $locator, $cacheDir = null)
    {
        if (null !== $cacheDir && is_file($cache = $cacheDir.'/templates.php')) {
            $this->cache = require $cache;
        }

        $this->locator = $locator;
    }

    
    protected function getCacheKey($template)
    {
        return $template->getLogicalName();
    }

    
    public function locate($template, $currentPath = null, $first = true)
    {
        if (!$template instanceof TemplateReferenceInterface) {
            throw new \InvalidArgumentException("The template must be an instance of TemplateReferenceInterface.");
        }

        $key = $this->getCacheKey($template);

        if (isset($this->cache[$key])) {
            return $this->cache[$key];
        }

        try {
            return $this->cache[$key] = $this->locator->locate($template->getPath(), $currentPath);
        } catch (\InvalidArgumentException $e) {
            throw new \InvalidArgumentException(sprintf('Unable to find template "%s" : "%s".', $template, $e->getMessage()), 0, $e);
        }
    }
}
}
 



namespace Symfony\Component\Templating
{


interface TemplateNameParserInterface
{
    
    public function parse($name);
}
}
 



namespace Symfony\Bundle\FrameworkBundle\Templating
{

use Symfony\Component\Templating\TemplateNameParserInterface;
use Symfony\Component\Templating\TemplateReferenceInterface;
use Symfony\Component\HttpKernel\KernelInterface;


class TemplateNameParser implements TemplateNameParserInterface
{
    protected $kernel;
    protected $cache;

    
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
        $this->cache = array();
    }

    
    public function parse($name)
    {
        if ($name instanceof TemplateReferenceInterface) {
            return $name;
        } elseif (isset($this->cache[$name])) {
            return $this->cache[$name];
        }

                $name = str_replace(':/', ':', preg_replace('#/{2,}#', '/', strtr($name, '\\', '/')));

        if (false !== strpos($name, '..')) {
            throw new \RuntimeException(sprintf('Template name "%s" contains invalid characters.', $name));
        }

        $parts = explode(':', $name);
        if (3 !== count($parts)) {
            throw new \InvalidArgumentException(sprintf('Template name "%s" is not valid (format is "bundle:section:template.format.engine").', $name));
        }

        $elements = explode('.', $parts[2]);
        if (3 > count($elements)) {
            throw new \InvalidArgumentException(sprintf('Template name "%s" is not valid (format is "bundle:section:template.format.engine").', $name));
        }
        $engine = array_pop($elements);
        $format = array_pop($elements);

        $template = new TemplateReference($parts[0], $parts[1], implode('.', $elements), $format, $engine);

        if ($template->get('bundle')) {
            try {
                $this->kernel->getBundle($template->get('bundle'));
            } catch (\Exception $e) {
                throw new \InvalidArgumentException(sprintf('Template name "%s" is not valid.', $name), 0, $e);
            }
        }

        return $this->cache[$name] = $template;
    }
}
}
 



namespace Symfony\Component\Templating
{


interface TemplateReferenceInterface
{
    
    public function all();

    
    public function set($name, $value);

    
    public function get($name);

    
    public function getPath();

    
    public function getLogicalName();
}
}
 



namespace Symfony\Component\Templating
{


class TemplateReference implements TemplateReferenceInterface
{
    protected $parameters;

    public function __construct($name = null, $engine = null)
    {
        $this->parameters = array(
            'name'   => $name,
            'engine' => $engine,
        );
    }

    public function __toString()
    {
        return $this->getLogicalName();
    }

    
    public function set($name, $value)
    {
        if (array_key_exists($name, $this->parameters)) {
            $this->parameters[$name] = $value;
        } else {
            throw new \InvalidArgumentException(sprintf('The template does not support the "%s" parameter.', $name));
        }

        return $this;
    }

    
    public function get($name)
    {
        if (array_key_exists($name, $this->parameters)) {
            return $this->parameters[$name];
        }

        throw new \InvalidArgumentException(sprintf('The template does not support the "%s" parameter.', $name));
    }

    
    public function all()
    {
        return $this->parameters;
    }

    
    public function getPath()
    {
        return $this->parameters['name'];
    }

    
    public function getLogicalName()
    {
        return $this->parameters['name'];
    }
}
}
 



namespace Symfony\Bundle\FrameworkBundle\Templating
{

use Symfony\Component\Templating\TemplateReference as BaseTemplateReference;


class TemplateReference extends BaseTemplateReference
{
    public function __construct($bundle = null, $controller = null, $name = null, $format = null, $engine = null)
    {
        $this->parameters = array(
            'bundle'     => $bundle,
            'controller' => $controller,
            'name'       => $name,
            'format'     => $format,
            'engine'     => $engine,
        );
    }

    
    public function getPath()
    {
        $controller = str_replace('\\', '/', $this->get('controller'));

        $path = (empty($controller) ? '' : $controller.'/').$this->get('name').'.'.$this->get('format').'.'.$this->get('engine');

        return empty($this->parameters['bundle']) ? 'views/'.$path : '@'.$this->get('bundle').'/Resources/views/'.$path;
    }

    
    public function getLogicalName()
    {
        return sprintf('%s:%s:%s.%s.%s', $this->parameters['bundle'], $this->parameters['controller'], $this->parameters['name'], $this->parameters['format'], $this->parameters['engine']);
    }
}
}
 



namespace Symfony\Bundle\SecurityBundle\Security
{

use Symfony\Component\Security\Http\Firewall\ExceptionListener;


class FirewallContext
{
    private $listeners;
    private $exceptionListener;

    public function __construct(array $listeners, ExceptionListener $exceptionListener = null)
    {
        $this->listeners = $listeners;
        $this->exceptionListener = $exceptionListener;
    }

    public function getContext()
    {
        return array($this->listeners, $this->exceptionListener);
    }
}
}
 



namespace Symfony\Component\Security\Http
{

use Symfony\Component\HttpFoundation\Request;


interface FirewallMapInterface
{
    
    public function getListeners(Request $request);
}
}
 



namespace Symfony\Bundle\SecurityBundle\Security
{

use Symfony\Component\Security\Http\FirewallMapInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;


class FirewallMap implements FirewallMapInterface
{
    protected $container;
    protected $map;

    public function __construct(ContainerInterface $container, array $map)
    {
        $this->container = $container;
        $this->map = $map;
    }

    public function getListeners(Request $request)
    {
        foreach ($this->map as $contextId => $requestMatcher) {
            if (null === $requestMatcher || $requestMatcher->matches($request)) {
                return $this->container->get($contextId)->getContext();
            }
        }

        return array(array(), null);
    }
}
}
 



namespace Symfony\Component\Config
{


class FileLocator implements FileLocatorInterface
{
    protected $paths;

    
    public function __construct($paths = array())
    {
        $this->paths = (array) $paths;
    }

    
    public function locate($name, $currentPath = null, $first = true)
    {
        if ($this->isAbsolutePath($name)) {
            if (!file_exists($name)) {
                throw new \InvalidArgumentException(sprintf('The file "%s" does not exist.', $name));
            }

            return $name;
        }

        $filepaths = array();
        if (null !== $currentPath && file_exists($file = $currentPath.DIRECTORY_SEPARATOR.$name)) {
            if (true === $first) {
                return $file;
            }
            $filepaths[] = $file;
        }

        foreach ($this->paths as $path) {
            if (file_exists($file = $path.DIRECTORY_SEPARATOR.$name)) {
                if (true === $first) {
                    return $file;
                }
                $filepaths[] = $file;
            }
        }

        if (!$filepaths) {
            throw new \InvalidArgumentException(sprintf('The file "%s" does not exist (in: %s%s).', $name, null !== $currentPath ? $currentPath.', ' : '', implode(', ', $this->paths)));
        }

        return array_values(array_unique($filepaths));
    }

    
    private function isAbsolutePath($file)
    {
        if ($file[0] == '/' || $file[0] == '\\'
            || (strlen($file) > 3 && ctype_alpha($file[0])
                && $file[1] == ':'
                && ($file[2] == '\\' || $file[2] == '/')
            )
            || null !== parse_url($file, PHP_URL_SCHEME)
        ) {
            return true;
        }

        return false;
    }
}
}
 



namespace Symfony\Component\EventDispatcher
{


interface EventDispatcherInterface
{
    
    public function dispatch($eventName, Event $event = null);

    
    public function addListener($eventName, $listener, $priority = 0);

    
    public function addSubscriber(EventSubscriberInterface $subscriber);

    
    public function removeListener($eventName, $listener);

    
    public function removeSubscriber(EventSubscriberInterface $subscriber);

    
    public function getListeners($eventName = null);

    
    public function hasListeners($eventName = null);
}
}
 



namespace Symfony\Component\EventDispatcher
{


class EventDispatcher implements EventDispatcherInterface
{
    private $listeners = array();
    private $sorted = array();

    
    public function dispatch($eventName, Event $event = null)
    {
        if (null === $event) {
            $event = new Event();
        }

        $event->setDispatcher($this);
        $event->setName($eventName);

        if (!isset($this->listeners[$eventName])) {
            return $event;
        }

        $this->doDispatch($this->getListeners($eventName), $eventName, $event);

        return $event;
    }

    
    public function getListeners($eventName = null)
    {
        if (null !== $eventName) {
            if (!isset($this->sorted[$eventName])) {
                $this->sortListeners($eventName);
            }

            return $this->sorted[$eventName];
        }

        foreach (array_keys($this->listeners) as $eventName) {
            if (!isset($this->sorted[$eventName])) {
                $this->sortListeners($eventName);
            }
        }

        return $this->sorted;
    }

    
    public function hasListeners($eventName = null)
    {
        return (Boolean) count($this->getListeners($eventName));
    }

    
    public function addListener($eventName, $listener, $priority = 0)
    {
        $this->listeners[$eventName][$priority][] = $listener;
        unset($this->sorted[$eventName]);
    }

    
    public function removeListener($eventName, $listener)
    {
        if (!isset($this->listeners[$eventName])) {
            return;
        }

        foreach ($this->listeners[$eventName] as $priority => $listeners) {
            if (false !== ($key = array_search($listener, $listeners))) {
                unset($this->listeners[$eventName][$priority][$key], $this->sorted[$eventName]);
            }
        }
    }

    
    public function addSubscriber(EventSubscriberInterface $subscriber)
    {
        foreach ($subscriber->getSubscribedEvents() as $eventName => $params) {
            if (is_string($params)) {
                $this->addListener($eventName, array($subscriber, $params));
            } elseif (is_string($params[0])) {
                $this->addListener($eventName, array($subscriber, $params[0]), isset($params[1]) ? $params[1] : 0);
            } else {
                foreach ($params as $listener) {
                    $this->addListener($eventName, array($subscriber, $listener[0]), isset($listener[1]) ? $listener[1] : 0);
                }
            }
        }
    }

    
    public function removeSubscriber(EventSubscriberInterface $subscriber)
    {
        foreach ($subscriber->getSubscribedEvents() as $eventName => $params) {
            if (is_array($params) && is_array($params[0])) {
                foreach ($params as $listener) {
                    $this->removeListener($eventName, array($subscriber, $listener[0]));
                }
            } else {
                $this->removeListener($eventName, array($subscriber, is_string($params) ? $params : $params[0]));
            }
        }
    }

    
    protected function doDispatch($listeners, $eventName, Event $event)
    {
        foreach ($listeners as $listener) {
            call_user_func($listener, $event);
            if ($event->isPropagationStopped()) {
                break;
            }
        }
    }

    
    private function sortListeners($eventName)
    {
        $this->sorted[$eventName] = array();

        if (isset($this->listeners[$eventName])) {
            krsort($this->listeners[$eventName]);
            $this->sorted[$eventName] = call_user_func_array('array_merge', $this->listeners[$eventName]);
        }
    }
}
}
 



namespace Symfony\Component\EventDispatcher
{

use Symfony\Component\DependencyInjection\ContainerInterface;


class ContainerAwareEventDispatcher extends EventDispatcher
{
    
    private $container;

    
    private $listenerIds = array();

    
    private $listeners = array();

    
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    
    public function addListenerService($eventName, $callback, $priority = 0)
    {
        if (!is_array($callback) || 2 !== count($callback)) {
            throw new \InvalidArgumentException('Expected an array("service", "method") argument');
        }

        $this->listenerIds[$eventName][] = array($callback[0], $callback[1], $priority);
    }

    public function removeListener($eventName, $listener)
    {
        $this->lazyLoad($eventName);

        if (isset($this->listeners[$eventName])) {
            foreach ($this->listeners[$eventName] as $key => $l) {
                foreach ($this->listenerIds[$eventName] as $i => $args) {
                    list($serviceId, $method, $priority) = $args;
                    if ($key === $serviceId.'.'.$method) {
                        if ($listener === array($l, $method)) {
                            unset($this->listeners[$eventName][$key]);
                            if (empty($this->listeners[$eventName])) {
                                unset($this->listeners[$eventName]);
                            }
                            unset($this->listenerIds[$eventName][$i]);
                            if (empty($this->listenerIds[$eventName])) {
                                unset($this->listenerIds[$eventName]);
                            }
                        }
                    }
                }
            }
        }

        parent::removeListener($eventName, $listener);
    }

    
    public function hasListeners($eventName = null)
    {
        if (null === $eventName) {
            return (Boolean) count($this->listenerIds) || (Boolean) count($this->listeners);
        }

        if (isset($this->listenerIds[$eventName])) {
            return true;
        }

        return parent::hasListeners($eventName);
    }

    
    public function getListeners($eventName = null)
    {
        if (null === $eventName) {
            foreach (array_keys($this->listenerIds) as $serviceEventName) {
                $this->lazyLoad($serviceEventName);
            }
        } else {
            $this->lazyLoad($eventName);
        }

        return parent::getListeners($eventName);
    }

    
    public function addSubscriberService($serviceId, $class)
    {
        foreach ($class::getSubscribedEvents() as $eventName => $params) {
            if (is_string($params)) {
                $this->listenerIds[$eventName][] = array($serviceId, $params, 0);
            } elseif (is_string($params[0])) {
                $this->listenerIds[$eventName][] = array($serviceId, $params[0], isset($params[1]) ? $params[1] : 0);
            } else {
                foreach ($params as $listener) {
                    $this->listenerIds[$eventName][] = array($serviceId, $listener[0], isset($listener[1]) ? $listener[1] : 0);
                }
            }
        }
    }

    
    public function dispatch($eventName, Event $event = null)
    {
        $this->lazyLoad($eventName);

        return parent::dispatch($eventName, $event);
    }

    public function getContainer()
    {
        return $this->container;
    }

    
    protected function lazyLoad($eventName)
    {
        if (isset($this->listenerIds[$eventName])) {
            foreach ($this->listenerIds[$eventName] as $args) {
                list($serviceId, $method, $priority) = $args;
                $listener = $this->container->get($serviceId);

                $key = $serviceId.'.'.$method;
                if (!isset($this->listeners[$eventName][$key])) {
                    $this->addListener($eventName, array($listener, $method), $priority);
                } elseif ($listener !== $this->listeners[$eventName][$key]) {
                    parent::removeListener($eventName, array($this->listeners[$eventName][$key], $method));
                    $this->addListener($eventName, array($listener, $method), $priority);
                }

                $this->listeners[$eventName][$key] = $listener;
            }
        }
    }
}
}
 



namespace Symfony\Component\EventDispatcher
{


class Event
{
    
    private $propagationStopped = false;

    
    private $dispatcher;

    
    private $name;

    
    public function isPropagationStopped()
    {
        return $this->propagationStopped;
    }

    
    public function stopPropagation()
    {
        $this->propagationStopped = true;
    }

    
    public function setDispatcher(EventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    
    public function getDispatcher()
    {
        return $this->dispatcher;
    }

    
    public function getName()
    {
        return $this->name;
    }

    
    public function setName($name)
    {
        $this->name = $name;
    }
}
}
 



namespace Symfony\Component\HttpFoundation
{


class ParameterBag implements \IteratorAggregate, \Countable
{
    
    protected $parameters;

    
    public function __construct(array $parameters = array())
    {
        $this->parameters = $parameters;
    }

    
    public function all()
    {
        return $this->parameters;
    }

    
    public function keys()
    {
        return array_keys($this->parameters);
    }

    
    public function replace(array $parameters = array())
    {
        $this->parameters = $parameters;
    }

    
    public function add(array $parameters = array())
    {
        $this->parameters = array_replace($this->parameters, $parameters);
    }

    
    public function get($path, $default = null, $deep = false)
    {
        if (!$deep || false === $pos = strpos($path, '[')) {
            return array_key_exists($path, $this->parameters) ? $this->parameters[$path] : $default;
        }

        $root = substr($path, 0, $pos);
        if (!array_key_exists($root, $this->parameters)) {
            return $default;
        }

        $value = $this->parameters[$root];
        $currentKey = null;
        for ($i = $pos, $c = strlen($path); $i < $c; $i++) {
            $char = $path[$i];

            if ('[' === $char) {
                if (null !== $currentKey) {
                    throw new \InvalidArgumentException(sprintf('Malformed path. Unexpected "[" at position %d.', $i));
                }

                $currentKey = '';
            } elseif (']' === $char) {
                if (null === $currentKey) {
                    throw new \InvalidArgumentException(sprintf('Malformed path. Unexpected "]" at position %d.', $i));
                }

                if (!is_array($value) || !array_key_exists($currentKey, $value)) {
                    return $default;
                }

                $value = $value[$currentKey];
                $currentKey = null;
            } else {
                if (null === $currentKey) {
                    throw new \InvalidArgumentException(sprintf('Malformed path. Unexpected "%s" at position %d.', $char, $i));
                }

                $currentKey .= $char;
            }
        }

        if (null !== $currentKey) {
            throw new \InvalidArgumentException(sprintf('Malformed path. Path must end with "]".'));
        }

        return $value;
    }

    
    public function set($key, $value)
    {
        $this->parameters[$key] = $value;
    }

    
    public function has($key)
    {
        return array_key_exists($key, $this->parameters);
    }

    
    public function remove($key)
    {
        unset($this->parameters[$key]);
    }

    
    public function getAlpha($key, $default = '', $deep = false)
    {
        return preg_replace('/[^[:alpha:]]/', '', $this->get($key, $default, $deep));
    }

    
    public function getAlnum($key, $default = '', $deep = false)
    {
        return preg_replace('/[^[:alnum:]]/', '', $this->get($key, $default, $deep));
    }

    
    public function getDigits($key, $default = '', $deep = false)
    {
                return str_replace(array('-', '+'), '', $this->filter($key, $default, $deep, FILTER_SANITIZE_NUMBER_INT));
    }

    
    public function getInt($key, $default = 0, $deep = false)
    {
        return (int) $this->get($key, $default, $deep);
    }

    
    public function filter($key, $default = null, $deep = false, $filter=FILTER_DEFAULT, $options=array())
    {
        $value = $this->get($key, $default, $deep);

                if (!is_array($options) && $options) {
            $options = array('flags' => $options);
        }

                if (is_array($value) && !isset($options['flags'])) {
            $options['flags'] = FILTER_REQUIRE_ARRAY;
        }

        return filter_var($value, $filter, $options);
    }

    
    public function getIterator()
    {
        return new \ArrayIterator($this->parameters);
    }

    
    public function count()
    {
        return count($this->parameters);
    }
}
}
 



namespace Symfony\Component\HttpFoundation
{

use Symfony\Component\HttpFoundation\File\UploadedFile;


class FileBag extends ParameterBag
{
    private static $fileKeys = array('error', 'name', 'size', 'tmp_name', 'type');

    
    public function __construct(array $parameters = array())
    {
        $this->replace($parameters);
    }

    
    public function replace(array $files = array())
    {
        $this->parameters = array();
        $this->add($files);
    }

    
    public function set($key, $value)
    {
        if (!is_array($value) && !$value instanceof UploadedFile) {
            throw new \InvalidArgumentException('An uploaded file must be an array or an instance of UploadedFile.');
        }

        parent::set($key, $this->convertFileInformation($value));
    }

    
    public function add(array $files = array())
    {
        foreach ($files as $key => $file) {
            $this->set($key, $file);
        }
    }

    
    protected function convertFileInformation($file)
    {
        if ($file instanceof UploadedFile) {
            return $file;
        }

        $file = $this->fixPhpFilesArray($file);
        if (is_array($file)) {
            $keys = array_keys($file);
            sort($keys);

            if ($keys == self::$fileKeys) {
                if (UPLOAD_ERR_NO_FILE == $file['error']) {
                    $file = null;
                } else {
                    $file = new UploadedFile($file['tmp_name'], $file['name'], $file['type'], $file['size'], $file['error']);
                }
            } else {
                $file = array_map(array($this, 'convertFileInformation'), $file);
            }
        }

        return $file;
    }

    
    protected function fixPhpFilesArray($data)
    {
        if (!is_array($data)) {
            return $data;
        }

        $keys = array_keys($data);
        sort($keys);

        if (self::$fileKeys != $keys || !isset($data['name']) || !is_array($data['name'])) {
            return $data;
        }

        $files = $data;
        foreach (self::$fileKeys as $k) {
            unset($files[$k]);
        }

        foreach (array_keys($data['name']) as $key) {
            $files[$key] = $this->fixPhpFilesArray(array(
                'error'    => $data['error'][$key],
                'name'     => $data['name'][$key],
                'type'     => $data['type'][$key],
                'tmp_name' => $data['tmp_name'][$key],
                'size'     => $data['size'][$key]
            ));
        }

        return $files;
    }
}
}
 



namespace Symfony\Component\HttpFoundation
{


class HeaderBag implements \IteratorAggregate, \Countable
{
    protected $headers;
    protected $cacheControl;

    
    public function __construct(array $headers = array())
    {
        $this->cacheControl = array();
        $this->headers = array();
        foreach ($headers as $key => $values) {
            $this->set($key, $values);
        }
    }

    
    public function __toString()
    {
        if (!$this->headers) {
            return '';
        }

        $max = max(array_map('strlen', array_keys($this->headers))) + 1;
        $content = '';
        ksort($this->headers);
        foreach ($this->headers as $name => $values) {
            $name = implode('-', array_map('ucfirst', explode('-', $name)));
            foreach ($values as $value) {
                $content .= sprintf("%-{$max}s %s\r\n", $name.':', $value);
            }
        }

        return $content;
    }

    
    public function all()
    {
        return $this->headers;
    }

    
    public function keys()
    {
        return array_keys($this->headers);
    }

    
    public function replace(array $headers = array())
    {
        $this->headers = array();
        $this->add($headers);
    }

    
    public function add(array $headers)
    {
        foreach ($headers as $key => $values) {
            $this->set($key, $values);
        }
    }

    
    public function get($key, $default = null, $first = true)
    {
        $key = strtr(strtolower($key), '_', '-');

        if (!array_key_exists($key, $this->headers)) {
            if (null === $default) {
                return $first ? null : array();
            }

            return $first ? $default : array($default);
        }

        if ($first) {
            return count($this->headers[$key]) ? $this->headers[$key][0] : $default;
        }

        return $this->headers[$key];
    }

    
    public function set($key, $values, $replace = true)
    {
        $key = strtr(strtolower($key), '_', '-');

        $values = array_values((array) $values);

        if (true === $replace || !isset($this->headers[$key])) {
            $this->headers[$key] = $values;
        } else {
            $this->headers[$key] = array_merge($this->headers[$key], $values);
        }

        if ('cache-control' === $key) {
            $this->cacheControl = $this->parseCacheControl($values[0]);
        }
    }

    
    public function has($key)
    {
        return array_key_exists(strtr(strtolower($key), '_', '-'), $this->headers);
    }

    
    public function contains($key, $value)
    {
        return in_array($value, $this->get($key, null, false));
    }

    
    public function remove($key)
    {
        $key = strtr(strtolower($key), '_', '-');

        unset($this->headers[$key]);

        if ('cache-control' === $key) {
            $this->cacheControl = array();
        }
    }

    
    public function getDate($key, \DateTime $default = null)
    {
        if (null === $value = $this->get($key)) {
            return $default;
        }

        if (false === $date = \DateTime::createFromFormat(DATE_RFC2822, $value)) {
            throw new \RuntimeException(sprintf('The %s HTTP header is not parseable (%s).', $key, $value));
        }

        return $date;
    }

    public function addCacheControlDirective($key, $value = true)
    {
        $this->cacheControl[$key] = $value;

        $this->set('Cache-Control', $this->getCacheControlHeader());
    }

    public function hasCacheControlDirective($key)
    {
        return array_key_exists($key, $this->cacheControl);
    }

    public function getCacheControlDirective($key)
    {
        return array_key_exists($key, $this->cacheControl) ? $this->cacheControl[$key] : null;
    }

    public function removeCacheControlDirective($key)
    {
        unset($this->cacheControl[$key]);

        $this->set('Cache-Control', $this->getCacheControlHeader());
    }

    
    public function getIterator()
    {
        return new \ArrayIterator($this->headers);
    }

    
    public function count()
    {
        return count($this->headers);
    }

    protected function getCacheControlHeader()
    {
        $parts = array();
        ksort($this->cacheControl);
        foreach ($this->cacheControl as $key => $value) {
            if (true === $value) {
                $parts[] = $key;
            } else {
                if (preg_match('#[^a-zA-Z0-9._-]#', $value)) {
                    $value = '"'.$value.'"';
                }

                $parts[] = "$key=$value";
            }
        }

        return implode(', ', $parts);
    }

    
    protected function parseCacheControl($header)
    {
        $cacheControl = array();
        preg_match_all('#([a-zA-Z][a-zA-Z_-]*)\s*(?:=(?:"([^"]*)"|([^ \t",;]*)))?#', $header, $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            $cacheControl[strtolower($match[1])] = isset($match[2]) && $match[2] ? $match[2] : (isset($match[3]) ? $match[3] : true);
        }

        return $cacheControl;
    }
}
}
 



namespace Symfony\Component\HttpFoundation
{

use Symfony\Component\HttpFoundation\Session\SessionInterface;


class Request
{
    protected static $trustProxy = false;

    
    public $attributes;

    
    public $request;

    
    public $query;

    
    public $server;

    
    public $files;

    
    public $cookies;

    
    public $headers;

    
    protected $content;

    
    protected $languages;

    
    protected $charsets;

    
    protected $acceptableContentTypes;

    
    protected $pathInfo;

    
    protected $requestUri;

    
    protected $baseUrl;

    
    protected $basePath;

    
    protected $method;

    
    protected $format;

    
    protected $session;

    
    protected $locale;

    
    protected $defaultLocale = 'en';

    
    protected static $formats;

    
    public function __construct(array $query = array(), array $request = array(), array $attributes = array(), array $cookies = array(), array $files = array(), array $server = array(), $content = null)
    {
        $this->initialize($query, $request, $attributes, $cookies, $files, $server, $content);
    }

    
    public function initialize(array $query = array(), array $request = array(), array $attributes = array(), array $cookies = array(), array $files = array(), array $server = array(), $content = null)
    {
        $this->request = new ParameterBag($request);
        $this->query = new ParameterBag($query);
        $this->attributes = new ParameterBag($attributes);
        $this->cookies = new ParameterBag($cookies);
        $this->files = new FileBag($files);
        $this->server = new ServerBag($server);
        $this->headers = new HeaderBag($this->server->getHeaders());

        $this->content = $content;
        $this->languages = null;
        $this->charsets = null;
        $this->acceptableContentTypes = null;
        $this->pathInfo = null;
        $this->requestUri = null;
        $this->baseUrl = null;
        $this->basePath = null;
        $this->method = null;
        $this->format = null;
    }

    
    public static function createFromGlobals()
    {
        $request = new static($_GET, $_POST, array(), $_COOKIE, $_FILES, $_SERVER);

        if (0 === strpos($request->server->get('CONTENT_TYPE'), 'application/x-www-form-urlencoded')
            && in_array(strtoupper($request->server->get('REQUEST_METHOD', 'GET')), array('PUT', 'DELETE', 'PATCH'))
        ) {
            parse_str($request->getContent(), $data);
            $request->request = new ParameterBag($data);
        }

        return $request;
    }

    
    public static function create($uri, $method = 'GET', $parameters = array(), $cookies = array(), $files = array(), $server = array(), $content = null)
    {
        $defaults = array(
            'SERVER_NAME'          => 'localhost',
            'SERVER_PORT'          => 80,
            'HTTP_HOST'            => 'localhost',
            'HTTP_USER_AGENT'      => 'Symfony/2.X',
            'HTTP_ACCEPT'          => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'HTTP_ACCEPT_LANGUAGE' => 'en-us,en;q=0.5',
            'HTTP_ACCEPT_CHARSET'  => 'ISO-8859-1,utf-8;q=0.7,*;q=0.7',
            'REMOTE_ADDR'          => '127.0.0.1',
            'SCRIPT_NAME'          => '',
            'SCRIPT_FILENAME'      => '',
            'SERVER_PROTOCOL'      => 'HTTP/1.1',
            'REQUEST_TIME'         => time(),
        );

        $components = parse_url($uri);
        if (isset($components['host'])) {
            $defaults['SERVER_NAME'] = $components['host'];
            $defaults['HTTP_HOST'] = $components['host'];
        }

        if (isset($components['scheme'])) {
            if ('https' === $components['scheme']) {
                $defaults['HTTPS'] = 'on';
                $defaults['SERVER_PORT'] = 443;
            }
        }

        if (isset($components['port'])) {
            $defaults['SERVER_PORT'] = $components['port'];
            $defaults['HTTP_HOST'] = $defaults['HTTP_HOST'].':'.$components['port'];
        }

        if (isset($components['user'])) {
            $defaults['PHP_AUTH_USER'] = $components['user'];
        }

        if (isset($components['pass'])) {
            $defaults['PHP_AUTH_PW'] = $components['pass'];
        }

        if (!isset($components['path'])) {
            $components['path'] = '';
        }

        switch (strtoupper($method)) {
            case 'POST':
            case 'PUT':
            case 'DELETE':
                $defaults['CONTENT_TYPE'] = 'application/x-www-form-urlencoded';
            case 'PATCH':
                $request = $parameters;
                $query = array();
                break;
            default:
                $request = array();
                $query = $parameters;
                break;
        }

        if (isset($components['query'])) {
            parse_str(html_entity_decode($components['query']), $qs);
            $query = array_replace($qs, $query);
        }
        $queryString = http_build_query($query, '', '&');

        $uri = $components['path'].('' !== $queryString ? '?'.$queryString : '');

        $server = array_replace($defaults, $server, array(
            'REQUEST_METHOD' => strtoupper($method),
            'PATH_INFO'      => '',
            'REQUEST_URI'    => $uri,
            'QUERY_STRING'   => $queryString,
        ));

        return new static($query, $request, array(), $cookies, $files, $server, $content);
    }

    
    public function duplicate(array $query = null, array $request = null, array $attributes = null, array $cookies = null, array $files = null, array $server = null)
    {
        $dup = clone $this;
        if ($query !== null) {
            $dup->query = new ParameterBag($query);
        }
        if ($request !== null) {
            $dup->request = new ParameterBag($request);
        }
        if ($attributes !== null) {
            $dup->attributes = new ParameterBag($attributes);
        }
        if ($cookies !== null) {
            $dup->cookies = new ParameterBag($cookies);
        }
        if ($files !== null) {
            $dup->files = new FileBag($files);
        }
        if ($server !== null) {
            $dup->server = new ServerBag($server);
            $dup->headers = new HeaderBag($dup->server->getHeaders());
        }
        $dup->languages = null;
        $dup->charsets = null;
        $dup->acceptableContentTypes = null;
        $dup->pathInfo = null;
        $dup->requestUri = null;
        $dup->baseUrl = null;
        $dup->basePath = null;
        $dup->method = null;
        $dup->format = null;

        return $dup;
    }

    
    public function __clone()
    {
        $this->query      = clone $this->query;
        $this->request    = clone $this->request;
        $this->attributes = clone $this->attributes;
        $this->cookies    = clone $this->cookies;
        $this->files      = clone $this->files;
        $this->server     = clone $this->server;
        $this->headers    = clone $this->headers;
    }

    
    public function __toString()
    {
        return
            sprintf('%s %s %s', $this->getMethod(), $this->getRequestUri(), $this->server->get('SERVER_PROTOCOL'))."\r\n".
            $this->headers."\r\n".
            $this->getContent();
    }

    
    public function overrideGlobals()
    {
        $_GET = $this->query->all();
        $_POST = $this->request->all();
        $_SERVER = $this->server->all();
        $_COOKIE = $this->cookies->all();

        foreach ($this->headers->all() as $key => $value) {
            $key = strtoupper(str_replace('-', '_', $key));
            if (in_array($key, array('CONTENT_TYPE', 'CONTENT_LENGTH'))) {
                $_SERVER[$key] = implode(', ', $value);
            } else {
                $_SERVER['HTTP_'.$key] = implode(', ', $value);
            }
        }

        $request = array('g' => $_GET, 'p' => $_POST, 'c' => $_COOKIE);

        $requestOrder = ini_get('request_order') ?: ini_get('variable_order');
        $requestOrder = preg_replace('#[^cgp]#', '', strtolower($requestOrder)) ?: 'gp';

        $_REQUEST = array();
        foreach (str_split($requestOrder) as $order) {
            $_REQUEST = array_merge($_REQUEST, $request[$order]);
        }
    }

    
    public static function trustProxyData()
    {
        self::$trustProxy = true;
    }

    
    public static function isProxyTrusted()
    {
        return self::$trustProxy;
    }

    
    public static function normalizeQueryString($qs)
    {
        if ('' == $qs) {
            return '';
        }

        $parts = array();
        $order = array();

        foreach (explode('&', $qs) as $param) {
            if ('' === $param || '=' === $param[0]) {
                                                                continue;
            }

            $keyValuePair = explode('=', $param, 2);

                                                $parts[] = isset($keyValuePair[1]) ?
                rawurlencode(urldecode($keyValuePair[0])).'='.rawurlencode(urldecode($keyValuePair[1])) :
                rawurlencode(urldecode($keyValuePair[0]));
            $order[] = urldecode($keyValuePair[0]);
        }

        array_multisort($order, SORT_ASC, $parts);

        return implode('&', $parts);
    }

    
    public function get($key, $default = null, $deep = false)
    {
        return $this->query->get($key, $this->attributes->get($key, $this->request->get($key, $default, $deep), $deep), $deep);
    }

    
    public function getSession()
    {
        return $this->session;
    }

    
    public function hasPreviousSession()
    {
                return $this->hasSession() && $this->cookies->has($this->session->getName());
    }

    
    public function hasSession()
    {
        return null !== $this->session;
    }

    
    public function setSession(SessionInterface $session)
    {
        $this->session = $session;
    }

    
    public function getClientIp()
    {
        if (self::$trustProxy) {
            if ($this->server->has('HTTP_CLIENT_IP')) {
                return $this->server->get('HTTP_CLIENT_IP');
            } elseif ($this->server->has('HTTP_X_FORWARDED_FOR')) {
                $clientIp = explode(',', $this->server->get('HTTP_X_FORWARDED_FOR'));

                foreach ($clientIp as $ipAddress) {
                    $cleanIpAddress = trim($ipAddress);

                    if (false !== filter_var($cleanIpAddress, FILTER_VALIDATE_IP)) {
                        return $cleanIpAddress;
                    }
                }

                return '';
            }
        }

        return $this->server->get('REMOTE_ADDR');
    }

    
    public function getScriptName()
    {
        return $this->server->get('SCRIPT_NAME', $this->server->get('ORIG_SCRIPT_NAME', ''));
    }

    
    public function getPathInfo()
    {
        if (null === $this->pathInfo) {
            $this->pathInfo = $this->preparePathInfo();
        }

        return $this->pathInfo;
    }

    
    public function getBasePath()
    {
        if (null === $this->basePath) {
            $this->basePath = $this->prepareBasePath();
        }

        return $this->basePath;
    }

    
    public function getBaseUrl()
    {
        if (null === $this->baseUrl) {
            $this->baseUrl = $this->prepareBaseUrl();
        }

        return $this->baseUrl;
    }

    
    public function getScheme()
    {
        return $this->isSecure() ? 'https' : 'http';
    }

    
    public function getPort()
    {
        if (self::$trustProxy && $this->headers->has('X-Forwarded-Port')) {
            return $this->headers->get('X-Forwarded-Port');
        }

        return $this->server->get('SERVER_PORT');
    }

    
    public function getUser()
    {
        return $this->server->get('PHP_AUTH_USER');
    }

    
    public function getPassword()
    {
        return $this->server->get('PHP_AUTH_PW');
    }

    
    public function getUserInfo()
    {
        $userinfo = $this->getUser();

        $pass = $this->getPassword();
        if ('' != $pass) {
           $userinfo .= ":$pass";
        }

        return $userinfo;
    }

    
    public function getHttpHost()
    {
        $scheme = $this->getScheme();
        $port   = $this->getPort();

        if (('http' == $scheme && $port == 80) || ('https' == $scheme && $port == 443)) {
            return $this->getHost();
        }

        return $this->getHost().':'.$port;
    }

    
    public function getRequestUri()
    {
        if (null === $this->requestUri) {
            $this->requestUri = $this->prepareRequestUri();
        }

        return $this->requestUri;
    }

    
    public function getSchemeAndHttpHost()
    {
        return $this->getScheme().'://'.(('' != $auth = $this->getUserInfo()) ? $auth.'@' : '').$this->getHttpHost();
    }

    
    public function getUri()
    {
        $qs = $this->getQueryString();
        if (null !== $qs) {
            $qs = '?'.$qs;
        }

        return $this->getSchemeAndHttpHost().$this->getBaseUrl().$this->getPathInfo().$qs;
    }

    
    public function getUriForPath($path)
    {
        return $this->getSchemeAndHttpHost().$this->getBaseUrl().$path;
    }

    
    public function getQueryString()
    {
        $qs = static::normalizeQueryString($this->server->get('QUERY_STRING'));

        return '' === $qs ? null : $qs;
    }

    
    public function isSecure()
    {
        return (
            (strtolower($this->server->get('HTTPS')) == 'on' || $this->server->get('HTTPS') == 1)
            ||
            (self::$trustProxy && strtolower($this->headers->get('SSL_HTTPS')) == 'on' || $this->headers->get('SSL_HTTPS') == 1)
            ||
            (self::$trustProxy && strtolower($this->headers->get('X_FORWARDED_PROTO')) == 'https')
        );
    }

    
    public function getHost()
    {
        if (self::$trustProxy && $host = $this->headers->get('X_FORWARDED_HOST')) {
            $elements = explode(',', $host);

            $host = trim($elements[count($elements) - 1]);
        } else {
            if (!$host = $this->headers->get('HOST')) {
                if (!$host = $this->server->get('SERVER_NAME')) {
                    $host = $this->server->get('SERVER_ADDR', '');
                }
            }
        }

                $host = preg_replace('/:\d+$/', '', $host);

                return trim(strtolower($host));
    }

    
    public function setMethod($method)
    {
        $this->method = null;
        $this->server->set('REQUEST_METHOD', $method);
    }

    
    public function getMethod()
    {
        if (null === $this->method) {
            $this->method = strtoupper($this->server->get('REQUEST_METHOD', 'GET'));
            if ('POST' === $this->method) {
                $this->method = strtoupper($this->headers->get('X-HTTP-METHOD-OVERRIDE', $this->request->get('_method', $this->query->get('_method', 'POST'))));
            }
        }

        return $this->method;
    }

    
    public function getMimeType($format)
    {
        if (null === static::$formats) {
            static::initializeFormats();
        }

        return isset(static::$formats[$format]) ? static::$formats[$format][0] : null;
    }

    
    public function getFormat($mimeType)
    {
        if (false !== $pos = strpos($mimeType, ';')) {
            $mimeType = substr($mimeType, 0, $pos);
        }

        if (null === static::$formats) {
            static::initializeFormats();
        }

        foreach (static::$formats as $format => $mimeTypes) {
            if (in_array($mimeType, (array) $mimeTypes)) {
                return $format;
            }
        }

        return null;
    }

    
    public function setFormat($format, $mimeTypes)
    {
        if (null === static::$formats) {
            static::initializeFormats();
        }

        static::$formats[$format] = is_array($mimeTypes) ? $mimeTypes : array($mimeTypes);
    }

    
    public function getRequestFormat($default = 'html')
    {
        if (null === $this->format) {
            $this->format = $this->get('_format', $default);
        }

        return $this->format;
    }

    
    public function setRequestFormat($format)
    {
        $this->format = $format;
    }

    
    public function getContentType()
    {
        return $this->getFormat($this->server->get('CONTENT_TYPE'));
    }

    
    public function setDefaultLocale($locale)
    {
        $this->setPhpDefaultLocale($this->defaultLocale = $locale);
    }

    
    public function setLocale($locale)
    {
        $this->setPhpDefaultLocale($this->locale = $locale);
    }

    
    public function getLocale()
    {
        return null === $this->locale ? $this->defaultLocale : $this->locale;
    }

    
    public function isMethod($method)
    {
        return $this->getMethod() === strtoupper($method);
    }

    
    public function isMethodSafe()
    {
        return in_array($this->getMethod(), array('GET', 'HEAD'));
    }

    
    public function getContent($asResource = false)
    {
        if (false === $this->content || (true === $asResource && null !== $this->content)) {
            throw new \LogicException('getContent() can only be called once when using the resource return type.');
        }

        if (true === $asResource) {
            $this->content = false;

            return fopen('php://input', 'rb');
        }

        if (null === $this->content) {
            $this->content = file_get_contents('php://input');
        }

        return $this->content;
    }

    
    public function getETags()
    {
        return preg_split('/\s*,\s*/', $this->headers->get('if_none_match'), null, PREG_SPLIT_NO_EMPTY);
    }

    
    public function isNoCache()
    {
        return $this->headers->hasCacheControlDirective('no-cache') || 'no-cache' == $this->headers->get('Pragma');
    }

    
    public function getPreferredLanguage(array $locales = null)
    {
        $preferredLanguages = $this->getLanguages();

        if (empty($locales)) {
            return isset($preferredLanguages[0]) ? $preferredLanguages[0] : null;
        }

        if (!$preferredLanguages) {
            return $locales[0];
        }

        $preferredLanguages = array_values(array_intersect($preferredLanguages, $locales));

        return isset($preferredLanguages[0]) ? $preferredLanguages[0] : $locales[0];
    }

    
    public function getLanguages()
    {
        if (null !== $this->languages) {
            return $this->languages;
        }

        $languages = $this->splitHttpAcceptHeader($this->headers->get('Accept-Language'));
        $this->languages = array();
        foreach ($languages as $lang => $q) {
            if (strstr($lang, '-')) {
                $codes = explode('-', $lang);
                if ($codes[0] == 'i') {
                                                                                if (count($codes) > 1) {
                        $lang = $codes[1];
                    }
                } else {
                    for ($i = 0, $max = count($codes); $i < $max; $i++) {
                        if ($i == 0) {
                            $lang = strtolower($codes[0]);
                        } else {
                            $lang .= '_'.strtoupper($codes[$i]);
                        }
                    }
                }
            }

            $this->languages[] = $lang;
        }

        return $this->languages;
    }

    
    public function getCharsets()
    {
        if (null !== $this->charsets) {
            return $this->charsets;
        }

        return $this->charsets = array_keys($this->splitHttpAcceptHeader($this->headers->get('Accept-Charset')));
    }

    
    public function getAcceptableContentTypes()
    {
        if (null !== $this->acceptableContentTypes) {
            return $this->acceptableContentTypes;
        }

        return $this->acceptableContentTypes = array_keys($this->splitHttpAcceptHeader($this->headers->get('Accept')));
    }

    
    public function isXmlHttpRequest()
    {
        return 'XMLHttpRequest' == $this->headers->get('X-Requested-With');
    }

    
    public function splitHttpAcceptHeader($header)
    {
        if (!$header) {
            return array();
        }

        $values = array();
        foreach (array_filter(explode(',', $header)) as $value) {
                        if (preg_match('/;\s*(q=.*$)/', $value, $match)) {
                $q     = (float) substr(trim($match[1]), 2);
                $value = trim(substr($value, 0, -strlen($match[0])));
            } else {
                $q = 1;
            }

            if (0 < $q) {
                $values[trim($value)] = $q;
            }
        }

        arsort($values);
        reset($values);

        return $values;
    }

    

    protected function prepareRequestUri()
    {
        $requestUri = '';

        if ($this->headers->has('X_REWRITE_URL') && false !== stripos(PHP_OS, 'WIN')) {
                        $requestUri = $this->headers->get('X_REWRITE_URL');
        } elseif ($this->server->get('IIS_WasUrlRewritten') == '1' && $this->server->get('UNENCODED_URL') != '') {
                        $requestUri = $this->server->get('UNENCODED_URL');
        } elseif ($this->server->has('REQUEST_URI')) {
            $requestUri = $this->server->get('REQUEST_URI');
                        $schemeAndHttpHost = $this->getSchemeAndHttpHost();
            if (strpos($requestUri, $schemeAndHttpHost) === 0) {
                $requestUri = substr($requestUri, strlen($schemeAndHttpHost));
            }
        } elseif ($this->server->has('ORIG_PATH_INFO')) {
                        $requestUri = $this->server->get('ORIG_PATH_INFO');
            if ('' != $this->server->get('QUERY_STRING')) {
                $requestUri .= '?'.$this->server->get('QUERY_STRING');
            }
        }

        return $requestUri;
    }

    
    protected function prepareBaseUrl()
    {
        $filename = basename($this->server->get('SCRIPT_FILENAME'));

        if (basename($this->server->get('SCRIPT_NAME')) === $filename) {
            $baseUrl = $this->server->get('SCRIPT_NAME');
        } elseif (basename($this->server->get('PHP_SELF')) === $filename) {
            $baseUrl = $this->server->get('PHP_SELF');
        } elseif (basename($this->server->get('ORIG_SCRIPT_NAME')) === $filename) {
            $baseUrl = $this->server->get('ORIG_SCRIPT_NAME');         } else {
                                    $path    = $this->server->get('PHP_SELF', '');
            $file    = $this->server->get('SCRIPT_FILENAME', '');
            $segs    = explode('/', trim($file, '/'));
            $segs    = array_reverse($segs);
            $index   = 0;
            $last    = count($segs);
            $baseUrl = '';
            do {
                $seg     = $segs[$index];
                $baseUrl = '/'.$seg.$baseUrl;
                ++$index;
            } while (($last > $index) && (false !== ($pos = strpos($path, $baseUrl))) && (0 != $pos));
        }

                $requestUri = $this->getRequestUri();

        if ($baseUrl && false !== $prefix = $this->getUrlencodedPrefix($requestUri, $baseUrl)) {
                        return $prefix;
        }

        if ($baseUrl && false !== $prefix = $this->getUrlencodedPrefix($requestUri, dirname($baseUrl))) {
                        return rtrim($prefix, '/');
        }

        $truncatedRequestUri = $requestUri;
        if (($pos = strpos($requestUri, '?')) !== false) {
            $truncatedRequestUri = substr($requestUri, 0, $pos);
        }

        $basename = basename($baseUrl);
        if (empty($basename) || !strpos(rawurldecode($truncatedRequestUri), $basename)) {
                        return '';
        }

                                if ((strlen($requestUri) >= strlen($baseUrl)) && ((false !== ($pos = strpos($requestUri, $baseUrl))) && ($pos !== 0))) {
            $baseUrl = substr($requestUri, 0, $pos + strlen($baseUrl));
        }

        return rtrim($baseUrl, '/');
    }

    
    protected function prepareBasePath()
    {
        $filename = basename($this->server->get('SCRIPT_FILENAME'));
        $baseUrl = $this->getBaseUrl();
        if (empty($baseUrl)) {
            return '';
        }

        if (basename($baseUrl) === $filename) {
            $basePath = dirname($baseUrl);
        } else {
            $basePath = $baseUrl;
        }

        if ('\\' === DIRECTORY_SEPARATOR) {
            $basePath = str_replace('\\', '/', $basePath);
        }

        return rtrim($basePath, '/');
    }

    
    protected function preparePathInfo()
    {
        $baseUrl = $this->getBaseUrl();

        if (null === ($requestUri = $this->getRequestUri())) {
            return '/';
        }

        $pathInfo = '/';

                if ($pos = strpos($requestUri, '?')) {
            $requestUri = substr($requestUri, 0, $pos);
        }

        if ((null !== $baseUrl) && (false === ($pathInfo = substr($requestUri, strlen($baseUrl))))) {
                        return '/';
        } elseif (null === $baseUrl) {
            return $requestUri;
        }

        return (string) $pathInfo;
    }

    
    protected static function initializeFormats()
    {
        static::$formats = array(
            'html' => array('text/html', 'application/xhtml+xml'),
            'txt'  => array('text/plain'),
            'js'   => array('application/javascript', 'application/x-javascript', 'text/javascript'),
            'css'  => array('text/css'),
            'json' => array('application/json', 'application/x-json'),
            'xml'  => array('text/xml', 'application/xml', 'application/x-xml'),
            'rdf'  => array('application/rdf+xml'),
            'atom' => array('application/atom+xml'),
            'rss'  => array('application/rss+xml'),
        );
    }

    
    private function setPhpDefaultLocale($locale)
    {
                                try {
            if (class_exists('Locale', false)) {
                \Locale::setDefault($locale);
            }
        } catch (\Exception $e) {
        }
    }

    
    private function getUrlencodedPrefix($string, $prefix)
    {
        if (0 !== strpos(rawurldecode($string), $prefix)) {
            return false;
        }

        $len = strlen($prefix);

        if (preg_match("#^(%[[:xdigit:]]{2}|.){{$len}}#", $string, $match)) {
            return $match[0];
        }

        return false;
    }
}
}
 



namespace Symfony\Component\HttpFoundation
{


interface RequestMatcherInterface
{
    
    public function matches(Request $request);
}
}
 



namespace Symfony\Component\HttpFoundation
{


class RequestMatcher implements RequestMatcherInterface
{
    
    private $path;

    
    private $host;

    
    private $methods;

    
    private $ip;

    
    private $attributes;

    public function __construct($path = null, $host = null, $methods = null, $ip = null, array $attributes = array())
    {
        $this->path = $path;
        $this->host = $host;
        $this->methods = $methods;
        $this->ip = $ip;
        $this->attributes = $attributes;
    }

    
    public function matchHost($regexp)
    {
        $this->host = $regexp;
    }

    
    public function matchPath($regexp)
    {
        $this->path = $regexp;
    }

    
    public function matchIp($ip)
    {
        $this->ip = $ip;
    }

    
    public function matchMethod($method)
    {
        $this->methods = array_map('strtoupper', is_array($method) ? $method : array($method));
    }

    
    public function matchAttribute($key, $regexp)
    {
        $this->attributes[$key] = $regexp;
    }

    
    public function matches(Request $request)
    {
        if (null !== $this->methods && !in_array($request->getMethod(), $this->methods)) {
            return false;
        }

        foreach ($this->attributes as $key => $pattern) {
            if (!preg_match('#'.str_replace('#', '\\#', $pattern).'#', $request->attributes->get($key))) {
                return false;
            }
        }

        if (null !== $this->path) {
            $path = str_replace('#', '\\#', $this->path);

            if (!preg_match('#'.$path.'#', rawurldecode($request->getPathInfo()))) {
                return false;
            }
        }

        if (null !== $this->host && !preg_match('#'.str_replace('#', '\\#', $this->host).'#', $request->getHost())) {
            return false;
        }

        if (null !== $this->ip && !$this->checkIp($request->getClientIp(), $this->ip)) {
            return false;
        }

        return true;
    }

    
    protected function checkIp($requestIp, $ip)
    {
                if (false !== strpos($requestIp, ':')) {
            return $this->checkIp6($requestIp, $ip);
        } else {
            return $this->checkIp4($requestIp, $ip);
        }
    }

    
    protected function checkIp4($requestIp, $ip)
    {
        if (false !== strpos($ip, '/')) {
            list($address, $netmask) = explode('/', $ip, 2);

            if ($netmask < 1 || $netmask > 32) {
                return false;
            }
        } else {
            $address = $ip;
            $netmask = 32;
        }

        return 0 === substr_compare(sprintf('%032b', ip2long($requestIp)), sprintf('%032b', ip2long($address)), 0, $netmask);
    }

    
    protected function checkIp6($requestIp, $ip)
    {
        if (!defined('AF_INET6')) {
            throw new \RuntimeException('Unable to check Ipv6. Check that PHP was not compiled with option "disable-ipv6".');
        }

        if (false !== strpos($ip, '/')) {
            list($address, $netmask) = explode('/', $ip, 2);
            
            if ($netmask < 1 || $netmask > 128) {
                return false;
            }
        } else {
            $address = $ip;
            $netmask = 128;
        }

        $bytesAddr = unpack("n*", inet_pton($address));
        $bytesTest = unpack("n*", inet_pton($requestIp));

        for ($i = 1, $ceil = ceil($netmask / 16); $i <= $ceil; $i++) {
            $left = $netmask - 16 * ($i-1);
            $left = ($left <= 16) ? $left : 16;
            $mask = ~(0xffff >> $left) & 0xffff;
            if (($bytesAddr[$i] & $mask) != ($bytesTest[$i] & $mask)) {
                return false;
            }
        }

        return true;
    }
}
}
 



namespace Symfony\Component\HttpFoundation
{


class Response
{
    
    public $headers;

    
    protected $content;

    
    protected $version;

    
    protected $statusCode;

    
    protected $statusText;

    
    protected $charset;

    
    public static $statusTexts = array(
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',                    200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',                  208 => 'Already Reported',              226 => 'IM Used',                       300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => 'Reserved',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',            400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',                                                       422 => 'Unprocessable Entity',                                                423 => 'Locked',                                                              424 => 'Failed Dependency',                                                   425 => 'Reserved for WebDAV advanced collections expired proposal',           426 => 'Upgrade Required',                                                    428 => 'Precondition Required',                                               429 => 'Too Many Requests',                                                   431 => 'Request Header Fields Too Large',                                     500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates (Experimental)',                              507 => 'Insufficient Storage',                                                508 => 'Loop Detected',                                                       510 => 'Not Extended',                                                        511 => 'Network Authentication Required',                                 );

    
    public function __construct($content = '', $status = 200, $headers = array())
    {
        $this->headers = new ResponseHeaderBag($headers);
        $this->setContent($content);
        $this->setStatusCode($status);
        $this->setProtocolVersion('1.0');
        if (!$this->headers->has('Date')) {
            $this->setDate(new \DateTime(null, new \DateTimeZone('UTC')));
        }
    }

    
    public static function create($content = '', $status = 200, $headers = array())
    {
        return new static($content, $status, $headers);
    }

    
    public function __toString()
    {
        return
            sprintf('HTTP/%s %s %s', $this->version, $this->statusCode, $this->statusText)."\r\n".
            $this->headers."\r\n".
            $this->getContent();
    }

    
    public function __clone()
    {
        $this->headers = clone $this->headers;
    }

    
    public function prepare(Request $request)
    {
        $headers = $this->headers;

        if ($this->isInformational() || in_array($this->statusCode, array(204, 304))) {
            $this->setContent(null);
        }

                if (!$headers->has('Content-Type')) {
            $format = $request->getRequestFormat();
            if (null !== $format && $mimeType = $request->getMimeType($format)) {
                $headers->set('Content-Type', $mimeType);
            }
        }

                $charset = $this->charset ?: 'UTF-8';
        if (!$headers->has('Content-Type')) {
            $headers->set('Content-Type', 'text/html; charset='.$charset);
        } elseif (0 === strpos($headers->get('Content-Type'), 'text/') && false === strpos($headers->get('Content-Type'), 'charset')) {
                        $headers->set('Content-Type', $headers->get('Content-Type').'; charset='.$charset);
        }

                if ($headers->has('Transfer-Encoding')) {
            $headers->remove('Content-Length');
        }

        if ('HEAD' === $request->getMethod()) {
                        $length = $headers->get('Content-Length');
            $this->setContent(null);
            if ($length) {
                $headers->set('Content-Length', $length);
            }
        }

        return $this;
    }

    
    public function sendHeaders()
    {
                if (headers_sent()) {
            return $this;
        }

                header(sprintf('HTTP/%s %s %s', $this->version, $this->statusCode, $this->statusText));

                foreach ($this->headers->all() as $name => $values) {
            foreach ($values as $value) {
                header($name.': '.$value, false);
            }
        }

                foreach ($this->headers->getCookies() as $cookie) {
            setcookie($cookie->getName(), $cookie->getValue(), $cookie->getExpiresTime(), $cookie->getPath(), $cookie->getDomain(), $cookie->isSecure(), $cookie->isHttpOnly());
        }

        return $this;
    }

    
    public function sendContent()
    {
        echo $this->content;

        return $this;
    }

    
    public function send()
    {
        $this->sendHeaders();
        $this->sendContent();

        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        } elseif ('cli' !== PHP_SAPI) {
                                    $previous = null;
            $obStatus = ob_get_status(1);
            while (($level = ob_get_level()) > 0 && $level !== $previous) {
                $previous = $level;
                if ($obStatus[$level - 1] && isset($obStatus[$level - 1]['del']) && $obStatus[$level - 1]['del']) {
                    ob_end_flush();
                }
            }
            flush();
        }

        return $this;
    }

    
    public function setContent($content)
    {
        if (null !== $content && !is_string($content) && !is_numeric($content) && !is_callable(array($content, '__toString'))) {
            throw new \UnexpectedValueException('The Response content must be a string or object implementing __toString(), "'.gettype($content).'" given.');
        }

        $this->content = (string) $content;

        return $this;
    }

    
    public function getContent()
    {
        return $this->content;
    }

    
    public function setProtocolVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    
    public function getProtocolVersion()
    {
        return $this->version;
    }

    
    public function setStatusCode($code, $text = null)
    {
        $this->statusCode = $code = (int) $code;
        if ($this->isInvalid()) {
            throw new \InvalidArgumentException(sprintf('The HTTP status code "%s" is not valid.', $code));
        }

        if (null === $text) {
            $this->statusText = isset(self::$statusTexts[$code]) ? self::$statusTexts[$code] : '';

            return $this;
        }

        if (false === $text) {
            $this->statusText = '';

            return $this;
        }

        $this->statusText = $text;

        return $this;
    }

    
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    
    public function setCharset($charset)
    {
        $this->charset = $charset;

        return $this;
    }

    
    public function getCharset()
    {
        return $this->charset;
    }

    
    public function isCacheable()
    {
        if (!in_array($this->statusCode, array(200, 203, 300, 301, 302, 404, 410))) {
            return false;
        }

        if ($this->headers->hasCacheControlDirective('no-store') || $this->headers->getCacheControlDirective('private')) {
            return false;
        }

        return $this->isValidateable() || $this->isFresh();
    }

    
    public function isFresh()
    {
        return $this->getTtl() > 0;
    }

    
    public function isValidateable()
    {
        return $this->headers->has('Last-Modified') || $this->headers->has('ETag');
    }

    
    public function setPrivate()
    {
        $this->headers->removeCacheControlDirective('public');
        $this->headers->addCacheControlDirective('private');

        return $this;
    }

    
    public function setPublic()
    {
        $this->headers->addCacheControlDirective('public');
        $this->headers->removeCacheControlDirective('private');

        return $this;
    }

    
    public function mustRevalidate()
    {
        return $this->headers->hasCacheControlDirective('must-revalidate') || $this->headers->has('proxy-revalidate');
    }

    
    public function getDate()
    {
        return $this->headers->getDate('Date');
    }

    
    public function setDate(\DateTime $date)
    {
        $date->setTimezone(new \DateTimeZone('UTC'));
        $this->headers->set('Date', $date->format('D, d M Y H:i:s').' GMT');

        return $this;
    }

    
    public function getAge()
    {
        if ($age = $this->headers->get('Age')) {
            return $age;
        }

        return max(time() - $this->getDate()->format('U'), 0);
    }

    
    public function expire()
    {
        if ($this->isFresh()) {
            $this->headers->set('Age', $this->getMaxAge());
        }

        return $this;
    }

    
    public function getExpires()
    {
        return $this->headers->getDate('Expires');
    }

    
    public function setExpires(\DateTime $date = null)
    {
        if (null === $date) {
            $this->headers->remove('Expires');
        } else {
            $date = clone $date;
            $date->setTimezone(new \DateTimeZone('UTC'));
            $this->headers->set('Expires', $date->format('D, d M Y H:i:s').' GMT');
        }

        return $this;
    }

    
    public function getMaxAge()
    {
        if ($age = $this->headers->getCacheControlDirective('s-maxage')) {
            return $age;
        }

        if ($age = $this->headers->getCacheControlDirective('max-age')) {
            return $age;
        }

        if (null !== $this->getExpires()) {
            return $this->getExpires()->format('U') - $this->getDate()->format('U');
        }

        return null;
    }

    
    public function setMaxAge($value)
    {
        $this->headers->addCacheControlDirective('max-age', $value);

        return $this;
    }

    
    public function setSharedMaxAge($value)
    {
        $this->setPublic();
        $this->headers->addCacheControlDirective('s-maxage', $value);

        return $this;
    }

    
    public function getTtl()
    {
        if ($maxAge = $this->getMaxAge()) {
            return $maxAge - $this->getAge();
        }

        return null;
    }

    
    public function setTtl($seconds)
    {
        $this->setSharedMaxAge($this->getAge() + $seconds);

        return $this;
    }

    
    public function setClientTtl($seconds)
    {
        $this->setMaxAge($this->getAge() + $seconds);

        return $this;
    }

    
    public function getLastModified()
    {
        return $this->headers->getDate('Last-Modified');
    }

    
    public function setLastModified(\DateTime $date = null)
    {
        if (null === $date) {
            $this->headers->remove('Last-Modified');
        } else {
            $date = clone $date;
            $date->setTimezone(new \DateTimeZone('UTC'));
            $this->headers->set('Last-Modified', $date->format('D, d M Y H:i:s').' GMT');
        }

        return $this;
    }

    
    public function getEtag()
    {
        return $this->headers->get('ETag');
    }

    
    public function setEtag($etag = null, $weak = false)
    {
        if (null === $etag) {
            $this->headers->remove('Etag');
        } else {
            if (0 !== strpos($etag, '"')) {
                $etag = '"'.$etag.'"';
            }

            $this->headers->set('ETag', (true === $weak ? 'W/' : '').$etag);
        }

        return $this;
    }

    
    public function setCache(array $options)
    {
        if ($diff = array_diff(array_keys($options), array('etag', 'last_modified', 'max_age', 's_maxage', 'private', 'public'))) {
            throw new \InvalidArgumentException(sprintf('Response does not support the following options: "%s".', implode('", "', array_values($diff))));
        }

        if (isset($options['etag'])) {
            $this->setEtag($options['etag']);
        }

        if (isset($options['last_modified'])) {
            $this->setLastModified($options['last_modified']);
        }

        if (isset($options['max_age'])) {
            $this->setMaxAge($options['max_age']);
        }

        if (isset($options['s_maxage'])) {
            $this->setSharedMaxAge($options['s_maxage']);
        }

        if (isset($options['public'])) {
            if ($options['public']) {
                $this->setPublic();
            } else {
                $this->setPrivate();
            }
        }

        if (isset($options['private'])) {
            if ($options['private']) {
                $this->setPrivate();
            } else {
                $this->setPublic();
            }
        }

        return $this;
    }

    
    public function setNotModified()
    {
        $this->setStatusCode(304);
        $this->setContent(null);

                foreach (array('Allow', 'Content-Encoding', 'Content-Language', 'Content-Length', 'Content-MD5', 'Content-Type', 'Last-Modified') as $header) {
            $this->headers->remove($header);
        }

        return $this;
    }

    
    public function hasVary()
    {
        return (Boolean) $this->headers->get('Vary');
    }

    
    public function getVary()
    {
        if (!$vary = $this->headers->get('Vary')) {
            return array();
        }

        return is_array($vary) ? $vary : preg_split('/[\s,]+/', $vary);
    }

    
    public function setVary($headers, $replace = true)
    {
        $this->headers->set('Vary', $headers, $replace);

        return $this;
    }

    
    public function isNotModified(Request $request)
    {
        if (!$request->isMethodSafe()) {
            return false;
        }

        $lastModified = $request->headers->get('If-Modified-Since');
        $notModified = false;
        if ($etags = $request->getEtags()) {
            $notModified = (in_array($this->getEtag(), $etags) || in_array('*', $etags)) && (!$lastModified || $this->headers->get('Last-Modified') == $lastModified);
        } elseif ($lastModified) {
            $notModified = $lastModified == $this->headers->get('Last-Modified');
        }

        if ($notModified) {
            $this->setNotModified();
        }

        return $notModified;
    }

        
    public function isInvalid()
    {
        return $this->statusCode < 100 || $this->statusCode >= 600;
    }

    
    public function isInformational()
    {
        return $this->statusCode >= 100 && $this->statusCode < 200;
    }

    
    public function isSuccessful()
    {
        return $this->statusCode >= 200 && $this->statusCode < 300;
    }

    
    public function isRedirection()
    {
        return $this->statusCode >= 300 && $this->statusCode < 400;
    }

    
    public function isClientError()
    {
        return $this->statusCode >= 400 && $this->statusCode < 500;
    }

    
    public function isServerError()
    {
        return $this->statusCode >= 500 && $this->statusCode < 600;
    }

    
    public function isOk()
    {
        return 200 === $this->statusCode;
    }

    
    public function isForbidden()
    {
        return 403 === $this->statusCode;
    }

    
    public function isNotFound()
    {
        return 404 === $this->statusCode;
    }

    
    public function isRedirect($location = null)
    {
        return in_array($this->statusCode, array(201, 301, 302, 303, 307, 308)) && (null === $location ?: $location == $this->headers->get('Location'));
    }

    
    public function isEmpty()
    {
        return in_array($this->statusCode, array(201, 204, 304));
    }
}
}
 



namespace Symfony\Component\HttpFoundation
{


class ResponseHeaderBag extends HeaderBag
{
    const COOKIES_FLAT           = 'flat';
    const COOKIES_ARRAY          = 'array';

    const DISPOSITION_ATTACHMENT = 'attachment';
    const DISPOSITION_INLINE     = 'inline';

    
    protected $computedCacheControl = array();

    
    protected $cookies              = array();

    
    public function __construct(array $headers = array())
    {
        parent::__construct($headers);

        if (!isset($this->headers['cache-control'])) {
            $this->set('cache-control', '');
        }
    }

    
    public function __toString()
    {
        $cookies = '';
        foreach ($this->getCookies() as $cookie) {
            $cookies .= 'Set-Cookie: '.$cookie."\r\n";
        }

        return parent::__toString().$cookies;
    }

    
    public function replace(array $headers = array())
    {
        parent::replace($headers);

        if (!isset($this->headers['cache-control'])) {
            $this->set('cache-control', '');
        }
    }

    
    public function set($key, $values, $replace = true)
    {
        parent::set($key, $values, $replace);

                if (in_array(strtr(strtolower($key), '_', '-'), array('cache-control', 'etag', 'last-modified', 'expires'))) {
            $computed = $this->computeCacheControlValue();
            $this->headers['cache-control'] = array($computed);
            $this->computedCacheControl = $this->parseCacheControl($computed);
        }
    }

    
    public function remove($key)
    {
        parent::remove($key);

        if ('cache-control' === strtr(strtolower($key), '_', '-')) {
            $this->computedCacheControl = array();
        }
    }

    
    public function hasCacheControlDirective($key)
    {
        return array_key_exists($key, $this->computedCacheControl);
    }

    
    public function getCacheControlDirective($key)
    {
        return array_key_exists($key, $this->computedCacheControl) ? $this->computedCacheControl[$key] : null;
    }

    
    public function setCookie(Cookie $cookie)
    {
        $this->cookies[$cookie->getDomain()][$cookie->getPath()][$cookie->getName()] = $cookie;
    }

    
    public function removeCookie($name, $path = '/', $domain = null)
    {
        if (null === $path) {
            $path = '/';
        }

        unset($this->cookies[$domain][$path][$name]);

        if (empty($this->cookies[$domain][$path])) {
            unset($this->cookies[$domain][$path]);

            if (empty($this->cookies[$domain])) {
                unset($this->cookies[$domain]);
            }
        }
    }

    
    public function getCookies($format = self::COOKIES_FLAT)
    {
        if (!in_array($format, array(self::COOKIES_FLAT, self::COOKIES_ARRAY))) {
            throw new \InvalidArgumentException(sprintf('Format "%s" invalid (%s).', $format, implode(', ', array(self::COOKIES_FLAT, self::COOKIES_ARRAY))));
        }

        if (self::COOKIES_ARRAY === $format) {
            return $this->cookies;
        }

        $flattenedCookies = array();
        foreach ($this->cookies as $path) {
            foreach ($path as $cookies) {
                foreach ($cookies as $cookie) {
                    $flattenedCookies[] = $cookie;
                }
            }
        }

        return $flattenedCookies;
    }

    
    public function clearCookie($name, $path = '/', $domain = null)
    {
        $this->setCookie(new Cookie($name, null, 1, $path, $domain));
    }

    
    public function makeDisposition($disposition, $filename, $filenameFallback = '')
    {
        if (!in_array($disposition, array(self::DISPOSITION_ATTACHMENT, self::DISPOSITION_INLINE))) {
            throw new \InvalidArgumentException(sprintf('The disposition must be either "%s" or "%s".', self::DISPOSITION_ATTACHMENT, self::DISPOSITION_INLINE));
        }

        if ('' == $filenameFallback) {
            $filenameFallback = $filename;
        }

                if (!preg_match('/^[\x20-\x7e]*$/', $filenameFallback)) {
            throw new \InvalidArgumentException('The filename fallback must only contain ASCII characters.');
        }

                if (false !== strpos($filenameFallback, '%')) {
            throw new \InvalidArgumentException('The filename fallback cannot contain the "%" character.');
        }

                if (false !== strpos($filename, '/') || false !== strpos($filename, '\\') || false !== strpos($filenameFallback, '/') || false !== strpos($filenameFallback, '\\')) {
            throw new \InvalidArgumentException('The filename and the fallback cannot contain the "/" and "\\" characters.');
        }

        $output = sprintf('%s; filename="%s"', $disposition, str_replace('"', '\\"', $filenameFallback));

        if ($filename !== $filenameFallback) {
            $output .= sprintf("; filename*=utf-8''%s", rawurlencode($filename));
        }

        return $output;
    }

    
    protected function computeCacheControlValue()
    {
        if (!$this->cacheControl && !$this->has('ETag') && !$this->has('Last-Modified') && !$this->has('Expires')) {
            return 'no-cache';
        }

        if (!$this->cacheControl) {
                        return 'private, must-revalidate';
        }

        $header = $this->getCacheControlHeader();
        if (isset($this->cacheControl['public']) || isset($this->cacheControl['private'])) {
            return $header;
        }

                if (!isset($this->cacheControl['s-maxage'])) {
            return $header.', private';
        }

        return $header;
    }
}
}
 



namespace Symfony\Component\HttpFoundation
{


class ServerBag extends ParameterBag
{
    
    public function getHeaders()
    {
        $headers = array();
        foreach ($this->parameters as $key => $value) {
            if (0 === strpos($key, 'HTTP_')) {
                $headers[substr($key, 5)] = $value;
            }
                        elseif (in_array($key, array('CONTENT_LENGTH', 'CONTENT_MD5', 'CONTENT_TYPE'))) {
                $headers[$key] = $value;
            }
        }

        if (isset($this->parameters['PHP_AUTH_USER'])) {
            $headers['PHP_AUTH_USER'] = $this->parameters['PHP_AUTH_USER'];
            $headers['PHP_AUTH_PW'] = isset($this->parameters['PHP_AUTH_PW']) ? $this->parameters['PHP_AUTH_PW'] : '';
        } else {
            

            $authorizationHeader = null;
            if (isset($this->parameters['HTTP_AUTHORIZATION'])) {
                $authorizationHeader = $this->parameters['HTTP_AUTHORIZATION'];
            } elseif (isset($this->parameters['REDIRECT_HTTP_AUTHORIZATION'])) {
                $authorizationHeader = $this->parameters['REDIRECT_HTTP_AUTHORIZATION'];
            }

                        if ((null !== $authorizationHeader) && (0 === stripos($authorizationHeader, 'basic'))) {
                $exploded = explode(':', base64_decode(substr($authorizationHeader, 6)));
                if (count($exploded) == 2) {
                    list($headers['PHP_AUTH_USER'], $headers['PHP_AUTH_PW']) = $exploded;
                }
            }
        }

                if (isset($headers['PHP_AUTH_USER'])) {
            $headers['AUTHORIZATION'] = 'Basic '.base64_encode($headers['PHP_AUTH_USER'].':'.$headers['PHP_AUTH_PW']);
        }

        return $headers;
    }
}
}
 



namespace Symfony\Component\HttpFoundation\Session
{

use Symfony\Component\HttpFoundation\Session\Storage\MetadataBag;


interface SessionInterface
{
    
    public function start();

    
    public function getId();

    
    public function setId($id);

    
    public function getName();

    
    public function setName($name);

    
    public function invalidate($lifetime = null);

    
    public function migrate($destroy = false, $lifetime = null);

    
    public function save();

    
    public function has($name);

    
    public function get($name, $default = null);

    
    public function set($name, $value);

    
    public function all();

    
    public function replace(array $attributes);

    
    public function remove($name);

    
    public function clear();

    
    public function isStarted();

    
    public function registerBag(SessionBagInterface $bag);

    
    public function getBag($name);

    
    public function getMetadataBag();
}
}
 



namespace Symfony\Component\HttpFoundation\Session
{

use Symfony\Component\HttpFoundation\Session\Storage\SessionStorageInterface;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBagInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionBagInterface;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;


class Session implements SessionInterface, \IteratorAggregate, \Countable
{
    
    protected $storage;

    
    private $flashName;

    
    private $attributeName;

    
    public function __construct(SessionStorageInterface $storage = null, AttributeBagInterface $attributes = null, FlashBagInterface $flashes = null)
    {
        $this->storage = $storage ?: new NativeSessionStorage();

        $attributes = $attributes ?: new AttributeBag();
        $this->attributeName = $attributes->getName();
        $this->registerBag($attributes);

        $flashes = $flashes ?: new FlashBag();
        $this->flashName = $flashes->getName();
        $this->registerBag($flashes);
    }

    
    public function start()
    {
        return $this->storage->start();
    }

    
    public function has($name)
    {
        return $this->storage->getBag($this->attributeName)->has($name);
    }

    
    public function get($name, $default = null)
    {
        return $this->storage->getBag($this->attributeName)->get($name, $default);
    }

    
    public function set($name, $value)
    {
        $this->storage->getBag($this->attributeName)->set($name, $value);
    }

    
    public function all()
    {
        return $this->storage->getBag($this->attributeName)->all();
    }

    
    public function replace(array $attributes)
    {
        $this->storage->getBag($this->attributeName)->replace($attributes);
    }

    
    public function remove($name)
    {
        return $this->storage->getBag($this->attributeName)->remove($name);
    }

    
    public function clear()
    {
        $this->storage->getBag($this->attributeName)->clear();
    }

    
    public function isStarted()
    {
        return $this->storage->isStarted();
    }

    
    public function getIterator()
    {
        return new \ArrayIterator($this->storage->getBag($this->attributeName)->all());
    }

    
    public function count()
    {
        return count($this->storage->getBag($this->attributeName)->all());
    }

    
    public function invalidate($lifetime = null)
    {
        $this->storage->clear();

        return $this->migrate(true, $lifetime);
    }

    
    public function migrate($destroy = false, $lifetime = null)
    {
        return $this->storage->regenerate($destroy, $lifetime);
    }

    
    public function save()
    {
        $this->storage->save();
    }

    
    public function getId()
    {
        return $this->storage->getId();
    }

    
    public function setId($id)
    {
        $this->storage->setId($id);
    }

    
    public function getName()
    {
        return $this->storage->getName();
    }

    
    public function setName($name)
    {
        $this->storage->setName($name);
    }

    
    public function getMetadataBag()
    {
        return $this->storage->getMetadataBag();
    }

    
    public function registerBag(SessionBagInterface $bag)
    {
        $this->storage->registerBag($bag);
    }

    
    public function getBag($name)
    {
        return $this->storage->getBag($name);
    }

    
    public function getFlashBag()
    {
        return $this->getBag($this->flashName);
    }

    
    
    public function getFlashes()
    {
        $all = $this->getBag($this->flashName)->all();

        $return = array();
        if ($all) {
            foreach ($all as $name => $array) {
                if (is_numeric(key($array))) {
                    $return[$name] = reset($array);
                } else {
                    $return[$name] = $array;
                }
            }
        }

        return $return;
    }

    
    public function setFlashes($values)
    {
        foreach ($values as $name => $value) {
            $this->getBag($this->flashName)->set($name, $value);
        }
    }

    
    public function getFlash($name, $default = null)
    {
        $return = $this->getBag($this->flashName)->get($name);

        return empty($return) ? $default : reset($return);
    }

    
    public function setFlash($name, $value)
    {
        $this->getBag($this->flashName)->set($name, $value);
    }

    
    public function hasFlash($name)
    {
        return $this->getBag($this->flashName)->has($name);
    }

    
    public function removeFlash($name)
    {
        $this->getBag($this->flashName)->get($name);
    }

    
    public function clearFlashes()
    {
        return $this->getBag($this->flashName)->clear();
    }
}
}
 



namespace Symfony\Component\HttpFoundation\Session\Storage\Handler
{



if (version_compare(phpversion(), '5.4.0', '>=')) {
    class NativeSessionHandler extends \SessionHandler {}
} else {
    class NativeSessionHandler {}
}
}
 



namespace Symfony\Component\HttpFoundation\Session\Storage\Handler
{


class NativeFileSessionHandler extends NativeSessionHandler
{
    
    public function __construct($savePath = null)
    {
        if (null === $savePath) {
            $savePath = ini_get('session.save_path');
        }

        $baseDir = $savePath;

        if ($count = substr_count($savePath, ';')) {
            if ($count > 2) {
                throw new \InvalidArgumentException(sprintf('Invalid argument $savePath \'%s\'', $savePath));
            }

                        $baseDir = ltrim(strrchr($savePath, ';'), ';');
        }

        if ($baseDir && !is_dir($baseDir)) {
            mkdir($baseDir, 0777, true);
        }

        ini_set('session.save_path', $savePath);
        ini_set('session.save_handler', 'files');
    }
}
}
 



namespace Symfony\Component\HttpFoundation\Session\Storage
{

use Symfony\Component\HttpFoundation\Session\SessionBagInterface;
use Symfony\Component\HttpFoundation\Session\Storage\MetadataBag;


interface SessionStorageInterface
{
    
    public function start();

    
    public function isStarted();

    
    public function getId();

    
    public function setId($id);

    
    public function getName();

    
    public function setName($name);

    
    public function regenerate($destroy = false, $lifetime = null);

    
    public function save();

    
    public function clear();

    
    public function getBag($name);

    
    public function registerBag(SessionBagInterface $bag);

    
    public function getMetadataBag();
}
}
 



namespace Symfony\Component\HttpFoundation\Session\Storage
{

use Symfony\Component\HttpFoundation\Session\SessionBagInterface;
use Symfony\Component\HttpFoundation\Session\Storage\MetadataBag;
use Symfony\Component\HttpFoundation\Session\Storage\Proxy\NativeProxy;
use Symfony\Component\HttpFoundation\Session\Storage\Proxy\AbstractProxy;
use Symfony\Component\HttpFoundation\Session\Storage\Proxy\SessionHandlerProxy;


class NativeSessionStorage implements SessionStorageInterface
{
    
    protected $bags;

    
    protected $started = false;

    
    protected $closed = false;

    
    protected $saveHandler;

    
    protected $metadataBag;

    
    public function __construct(array $options = array(), $handler = null, MetadataBag $metaBag = null)
    {
        ini_set('session.cache_limiter', '');         ini_set('session.use_cookies', 1);

        if (version_compare(phpversion(), '5.4.0', '>=')) {
            session_register_shutdown();
        } else {
            register_shutdown_function('session_write_close');
        }

        $this->setMetadataBag($metaBag);
        $this->setOptions($options);
        $this->setSaveHandler($handler);
    }

    
    public function getSaveHandler()
    {
        return $this->saveHandler;
    }

    
    public function start()
    {
        if ($this->started && !$this->closed) {
            return true;
        }

                if (!$this->started && !$this->closed && $this->saveHandler->isActive()
            && $this->saveHandler->isSessionHandlerInterface()) {
            $this->loadSession();

            return true;
        }

        if (ini_get('session.use_cookies') && headers_sent()) {
            throw new \RuntimeException('Failed to start the session because headers have already been sent.');
        }

                if (!session_start()) {
            throw new \RuntimeException('Failed to start the session');
        }

        $this->loadSession();

        if (!$this->saveHandler->isWrapper() && !$this->saveHandler->isSessionHandlerInterface()) {
            $this->saveHandler->setActive(false);
        }

        return true;
    }

    
    public function getId()
    {
        if (!$this->started) {
            return '';         }

        return $this->saveHandler->getId();
    }

    
    public function setId($id)
    {
        $this->saveHandler->setId($id);
    }

    
    public function getName()
    {
        return $this->saveHandler->getName();
    }

    
    public function setName($name)
    {
        $this->saveHandler->setName($name);
    }

    
    public function regenerate($destroy = false, $lifetime = null)
    {
        if (null !== $lifetime) {
            ini_set('session.cookie_lifetime', $lifetime);
        }

        if ($destroy) {
            $this->metadataBag->stampNew();
        }

        return session_regenerate_id($destroy);
    }

    
    public function save()
    {
        session_write_close();

        if (!$this->saveHandler->isWrapper() && !$this->getSaveHandler()->isSessionHandlerInterface()) {
            $this->saveHandler->setActive(false);
        }

        $this->closed = true;
    }

    
    public function clear()
    {
                foreach ($this->bags as $bag) {
            $bag->clear();
        }

                $_SESSION = array();

                $this->loadSession();
    }

    
    public function registerBag(SessionBagInterface $bag)
    {
        $this->bags[$bag->getName()] = $bag;
    }

    
    public function getBag($name)
    {
        if (!isset($this->bags[$name])) {
            throw new \InvalidArgumentException(sprintf('The SessionBagInterface %s is not registered.', $name));
        }

        if ($this->saveHandler->isActive() && !$this->started) {
            $this->loadSession();
        } elseif (!$this->started) {
            $this->start();
        }

        return $this->bags[$name];
    }

    
    public function setMetadataBag(MetadataBag $metaBag = null)
    {
        if (null === $metaBag) {
            $metaBag = new MetadataBag();
        }

        $this->metadataBag = $metaBag;
    }

    
    public function getMetadataBag()
    {
        return $this->metadataBag;
    }

    
    public function isStarted()
    {
        return $this->started;
    }

    
    public function setOptions(array $options)
    {
        $validOptions = array_flip(array(
            'cache_limiter', 'cookie_domain', 'cookie_httponly',
            'cookie_lifetime', 'cookie_path', 'cookie_secure',
            'entropy_file', 'entropy_length', 'gc_divisor',
            'gc_maxlifetime', 'gc_probability', 'hash_bits_per_character',
            'hash_function', 'name', 'referer_check',
            'serialize_handler', 'use_cookies',
            'use_only_cookies', 'use_trans_sid', 'upload_progress.enabled',
            'upload_progress.cleanup', 'upload_progress.prefix', 'upload_progress.name',
            'upload_progress.freq', 'upload_progress.min-freq', 'url_rewriter.tags',
        ));

        foreach ($options as $key => $value) {
            if (isset($validOptions[$key])) {
                ini_set('session.'.$key, $value);
            }
        }
    }

    
    public function setSaveHandler($saveHandler = null)
    {
                if (!$saveHandler instanceof AbstractProxy && $saveHandler instanceof \SessionHandlerInterface) {
            $saveHandler = new SessionHandlerProxy($saveHandler);
        } elseif (!$saveHandler instanceof AbstractProxy) {
            $saveHandler = new NativeProxy();
        }

        $this->saveHandler = $saveHandler;

        if ($this->saveHandler instanceof \SessionHandlerInterface) {
            if (version_compare(phpversion(), '5.4.0', '>=')) {
                session_set_save_handler($this->saveHandler, false);
            } else {
                session_set_save_handler(
                    array($this->saveHandler, 'open'),
                    array($this->saveHandler, 'close'),
                    array($this->saveHandler, 'read'),
                    array($this->saveHandler, 'write'),
                    array($this->saveHandler, 'destroy'),
                    array($this->saveHandler, 'gc')
                );
            }
        }
    }

    
    protected function loadSession(array &$session = null)
    {
        if (null === $session) {
            $session = &$_SESSION;
        }

        $bags = array_merge($this->bags, array($this->metadataBag));

        foreach ($bags as $bag) {
            $key = $bag->getStorageKey();
            $session[$key] = isset($session[$key]) ? $session[$key] : array();
            $bag->initialize($session[$key]);
        }

        $this->started = true;
        $this->closed = false;
    }
}
}
 



namespace Symfony\Component\HttpFoundation\Session\Storage\Proxy
{


abstract class AbstractProxy
{
    
    protected $wrapper = false;

    
    protected $active = false;

    
    protected $saveHandlerName;

    
    public function getSaveHandlerName()
    {
        return $this->saveHandlerName;
    }

    
    public function isSessionHandlerInterface()
    {
        return ($this instanceof \SessionHandlerInterface);
    }

    
    public function isWrapper()
    {
        return $this->wrapper;
    }

    
    public function isActive()
    {
        return $this->active;
    }

    
    public function setActive($flag)
    {
        $this->active = (bool) $flag;
    }

    
    public function getId()
    {
        return session_id();
    }

    
    public function setId($id)
    {
        if ($this->isActive()) {
            throw new \LogicException('Cannot change the ID of an active session');
        }

        session_id($id);
    }

    
    public function getName()
    {
        return session_name();
    }

    
    public function setName($name)
    {
        if ($this->isActive()) {
            throw new \LogicException('Cannot change the name of an active session');
        }

        session_name($name);
    }
}
}

namespace
{

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
/**
 * SessionHandlerInterface
 *
 * Provides forward compatibility with PHP 5.4
 *
 * Extensive documentation can be found at php.net, see links:
 *
 * @see http://php.net/sessionhandlerinterface
 * @see http://php.net/session.customhandler
 * @see http://php.net/session-set-save-handler
 *
 * @author Drak <drak@zikula.org>
 */
interface SessionHandlerInterface
{
    /**
     * Open session.
     *
     * @see http://php.net/sessionhandlerinterface.open
     *
     * @param string $savePath    Save path.
     * @param string $sessionName Session Name.
     *
     * @throws \RuntimeException If something goes wrong starting the session.
     *
     * @return boolean
     */
    public function open($savePath, $sessionName);
    /**
     * Close session.
     *
     * @see http://php.net/sessionhandlerinterface.close
     *
     * @return boolean
     */
    public function close();
    /**
     * Read session.
     *
     * @see http://php.net/sessionhandlerinterface.read
     *
     * @throws \RuntimeException On fatal error but not "record not found".
     *
     * @return string String as stored in persistent storage or empty string in all other cases.
     */
    public function read($sessionId);
    /**
     * Commit session to storage.
     *
     * @see http://php.net/sessionhandlerinterface.write
     *
     * @param string $sessionId Session ID.
     * @param string $data      Session serialized data to save.
     *
     * @return boolean
     */
    public function write($sessionId, $data);
    /**
     * Destroys this session.
     *
     * @see http://php.net/sessionhandlerinterface.destroy
     *
     * @param string $sessionId Session ID.
     *
     * @throws \RuntimeException On fatal error.
     *
     * @return boolean
     */
    public function destroy($sessionId);
    /**
     * Garbage collection for storage.
     *
     * @see http://php.net/sessionhandlerinterface.gc
     *
     * @param integer $lifetime Max lifetime in seconds to keep sessions stored.
     *
     * @throws \RuntimeException On fatal error.
     *
     * @return boolean
     */
    public function gc($lifetime);
}

}
 



namespace Symfony\Component\HttpFoundation\Session\Storage\Proxy
{


class SessionHandlerProxy extends AbstractProxy implements \SessionHandlerInterface
{
    
    protected $handler;

    
    public function __construct(\SessionHandlerInterface $handler)
    {
        $this->handler = $handler;
        $this->wrapper = ($handler instanceof \SessionHandler);
        $this->saveHandlerName = $this->wrapper ? ini_get('session.save_handler') : 'user';
    }

    
    
    public function open($savePath, $sessionName)
    {
        $return = (bool) $this->handler->open($savePath, $sessionName);

        if (true === $return) {
            $this->active = true;
        }

        return $return;
    }

    
    public function close()
    {
        $this->active = false;

        return (bool) $this->handler->close();
    }

    
    public function read($id)
    {
        return (string) $this->handler->read($id);
    }

    
    public function write($id, $data)
    {
        return (bool) $this->handler->write($id, $data);
    }

    
    public function destroy($id)
    {
        return (bool) $this->handler->destroy($id);
    }

    
    public function gc($maxlifetime)
    {
        return (bool) $this->handler->gc($maxlifetime);
    }
}
}
 



namespace Symfony\Component\HttpKernel\Config
{

use Symfony\Component\Config\FileLocator as BaseFileLocator;
use Symfony\Component\HttpKernel\KernelInterface;


class FileLocator extends BaseFileLocator
{
    private $kernel;
    private $path;

    
    public function __construct(KernelInterface $kernel, $path = null, array $paths = array())
    {
        $this->kernel = $kernel;
        $this->path = $path;
        $paths[] = $path;

        parent::__construct($paths);
    }

    
    public function locate($file, $currentPath = null, $first = true)
    {
        if ('@' === $file[0]) {
            return $this->kernel->locateResource($file, $this->path, $first);
        }

        return parent::locate($file, $currentPath, $first);
    }
}
}
 



namespace Symfony\Component\HttpKernel\EventListener
{

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


class ResponseListener implements EventSubscriberInterface
{
    private $charset;

    public function __construct($charset)
    {
        $this->charset = $charset;
    }

    
    public function onKernelResponse(FilterResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        $response = $event->getResponse();

        if (null === $response->getCharset()) {
            $response->setCharset($this->charset);
        }

        $response->prepare($event->getRequest());
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::RESPONSE => 'onKernelResponse',
        );
    }
}
}
 



namespace Symfony\Component\HttpKernel\EventListener
{

use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Routing\Matcher\RequestMatcherInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RequestContextAwareInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


class RouterListener implements EventSubscriberInterface
{
    private $matcher;
    private $context;
    private $logger;

    
    public function __construct($matcher, RequestContext $context = null, LoggerInterface $logger = null)
    {
        if (!$matcher instanceof UrlMatcherInterface && !$matcher instanceof RequestMatcherInterface) {
            throw new \InvalidArgumentException('Matcher must either implement UrlMatcherInterface or RequestMatcherInterface.');
        }

        if (null === $context && !$matcher instanceof RequestContextAwareInterface) {
            throw new \InvalidArgumentException('You must either pass a RequestContext or the matcher must implement RequestContextAwareInterface.');
        }

        $this->matcher = $matcher;
        $this->context = $context ?: $matcher->getContext();
        $this->logger = $logger;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

                $this->context->fromRequest($request);

        if ($request->attributes->has('_controller')) {
                        return;
        }

                try {
                        if ($this->matcher instanceof RequestMatcherInterface) {
                $parameters = $this->matcher->matchRequest($request);
            } else {
                $parameters = $this->matcher->match($request->getPathInfo());
            }

            if (null !== $this->logger) {
                $this->logger->info(sprintf('Matched route "%s" (parameters: %s)', $parameters['_route'], $this->parametersToString($parameters)));
            }

            $request->attributes->add($parameters);
            unset($parameters['_route']);
            unset($parameters['_controller']);
            $request->attributes->set('_route_params', $parameters);
        } catch (ResourceNotFoundException $e) {
            $message = sprintf('No route found for "%s %s"', $request->getMethod(), $request->getPathInfo());

            throw new NotFoundHttpException($message, $e);
        } catch (MethodNotAllowedException $e) {
            $message = sprintf('No route found for "%s %s": Method Not Allowed (Allow: %s)', $request->getMethod(), $request->getPathInfo(), strtoupper(implode(', ', $e->getAllowedMethods())));

            throw new MethodNotAllowedHttpException($e->getAllowedMethods(), $message, $e);
        }
    }

    private function parametersToString(array $parameters)
    {
        $pieces = array();
        foreach ($parameters as $key => $val) {
            $pieces[] = sprintf('"%s": "%s"', $key, (is_string($val) ? $val : json_encode($val)));
        }

        return implode(', ', $pieces);
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => array(array('onKernelRequest', 32)),
        );
    }
}
}
 



namespace Symfony\Component\HttpKernel\Event
{

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\Event;


class KernelEvent extends Event
{
    
    private $kernel;

    
    private $request;

    
    private $requestType;

    public function __construct(HttpKernelInterface $kernel, Request $request, $requestType)
    {
        $this->kernel = $kernel;
        $this->request = $request;
        $this->requestType = $requestType;
    }

    
    public function getKernel()
    {
        return $this->kernel;
    }

    
    public function getRequest()
    {
        return $this->request;
    }

    
    public function getRequestType()
    {
        return $this->requestType;
    }
}
}
 



namespace Symfony\Component\HttpKernel\Event
{

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request;


class FilterControllerEvent extends KernelEvent
{
    
    private $controller;

    public function __construct(HttpKernelInterface $kernel, $controller, Request $request, $requestType)
    {
        parent::__construct($kernel, $request, $requestType);

        $this->setController($controller);
    }

    
    public function getController()
    {
        return $this->controller;
    }

    
    public function setController($controller)
    {
                if (!is_callable($controller)) {
            throw new \LogicException(sprintf('The controller must be a callable (%s given).', $this->varToString($controller)));
        }

        $this->controller = $controller;
    }

    private function varToString($var)
    {
        if (is_object($var)) {
            return sprintf('Object(%s)', get_class($var));
        }

        if (is_array($var)) {
            $a = array();
            foreach ($var as $k => $v) {
                $a[] = sprintf('%s => %s', $k, $this->varToString($v));
            }

            return sprintf("Array(%s)", implode(', ', $a));
        }

        if (is_resource($var)) {
            return sprintf('Resource(%s)', get_resource_type($var));
        }

        if (null === $var) {
            return 'null';
        }

        if (false === $var) {
            return 'false';
        }

        if (true === $var) {
            return 'true';
        }

        return (string) $var;
    }
}
}
 



namespace Symfony\Component\HttpKernel\Event
{

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class FilterResponseEvent extends KernelEvent
{
    
    private $response;

    public function __construct(HttpKernelInterface $kernel, Request $request, $requestType, Response $response)
    {
        parent::__construct($kernel, $request, $requestType);

        $this->setResponse($response);
    }

    
    public function getResponse()
    {
        return $this->response;
    }

    
    public function setResponse(Response $response)
    {
        $this->response = $response;
    }
}
}
 



namespace Symfony\Component\HttpKernel\Event
{

use Symfony\Component\HttpFoundation\Response;


class GetResponseEvent extends KernelEvent
{
    
    private $response;

    
    public function getResponse()
    {
        return $this->response;
    }

    
    public function setResponse(Response $response)
    {
        $this->response = $response;

        $this->stopPropagation();
    }

    
    public function hasResponse()
    {
        return null !== $this->response;
    }
}
}
 



namespace Symfony\Component\HttpKernel\Event
{

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request;


class GetResponseForControllerResultEvent extends GetResponseEvent
{
    
    private $controllerResult;

    public function __construct(HttpKernelInterface $kernel, Request $request, $requestType, $controllerResult)
    {
        parent::__construct($kernel, $request, $requestType);

        $this->controllerResult = $controllerResult;
    }

    
    public function getControllerResult()
    {
        return $this->controllerResult;
    }
}
}
 



namespace Symfony\Component\HttpKernel\Event
{

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request;


class GetResponseForExceptionEvent extends GetResponseEvent
{
    
    private $exception;

    public function __construct(HttpKernelInterface $kernel, Request $request, $requestType, \Exception $e)
    {
        parent::__construct($kernel, $request, $requestType);

        $this->setException($e);
    }

    
    public function getException()
    {
        return $this->exception;
    }

    
    public function setException(\Exception $exception)
    {
        $this->exception = $exception;
    }
}
}
 



namespace Symfony\Component\HttpKernel
{


final class KernelEvents
{
    
    const REQUEST = 'kernel.request';

    
    const EXCEPTION = 'kernel.exception';

    
    const VIEW = 'kernel.view';

    
    const CONTROLLER = 'kernel.controller';

    
    const RESPONSE = 'kernel.response';

    
    const TERMINATE = 'kernel.terminate';
}
}
 



namespace Symfony\Component\Routing\Generator
{


interface ConfigurableRequirementsInterface
{
    
    public function setStrictRequirements($enabled);

    
    public function isStrictRequirements();
}
}
 



namespace Symfony\Component\Routing\Generator
{

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Exception\InvalidParameterException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Exception\MissingMandatoryParametersException;
use Symfony\Component\HttpKernel\Log\LoggerInterface;


class UrlGenerator implements UrlGeneratorInterface, ConfigurableRequirementsInterface
{
    protected $context;
    protected $strictRequirements = true;
    protected $logger;

    
    protected $decodedChars = array(
                                '%2F' => '/',
                        '%40' => '@',
        '%3A' => ':',
                        '%3B' => ';',
        '%2C' => ',',
        '%3D' => '=',
        '%2B' => '+',
        '%21' => '!',
        '%2A' => '*',
        '%7C' => '|',
    );

    protected $routes;

    
    public function __construct(RouteCollection $routes, RequestContext $context, LoggerInterface $logger = null)
    {
        $this->routes = $routes;
        $this->context = $context;
        $this->logger = $logger;
    }

    
    public function setContext(RequestContext $context)
    {
        $this->context = $context;
    }

    
    public function getContext()
    {
        return $this->context;
    }

    
    public function setStrictRequirements($enabled)
    {
        $this->strictRequirements = (Boolean) $enabled;
    }

    
    public function isStrictRequirements()
    {
        return $this->strictRequirements;
    }

    
    public function generate($name, $parameters = array(), $absolute = false)
    {
        if (null === $route = $this->routes->get($name)) {
            throw new RouteNotFoundException(sprintf('Route "%s" does not exist.', $name));
        }

                $compiledRoute = $route->compile();

        return $this->doGenerate($compiledRoute->getVariables(), $route->getDefaults(), $route->getRequirements(), $compiledRoute->getTokens(), $parameters, $name, $absolute);
    }

    
    protected function doGenerate($variables, $defaults, $requirements, $tokens, $parameters, $name, $absolute)
    {
        $variables = array_flip($variables);

        $originParameters = $parameters;
        $parameters = array_replace($this->context->getParameters(), $parameters);
        $tparams = array_replace($defaults, $parameters);

                if ($diff = array_diff_key($variables, $tparams)) {
            throw new MissingMandatoryParametersException(sprintf('The "%s" route has some missing mandatory parameters ("%s").', $name, implode('", "', array_keys($diff))));
        }

        $url = '';
        $optional = true;
        foreach ($tokens as $token) {
            if ('variable' === $token[0]) {
                if (false === $optional || !array_key_exists($token[3], $defaults) || (isset($parameters[$token[3]]) && (string) $parameters[$token[3]] != (string) $defaults[$token[3]])) {
                    if (!$isEmpty = in_array($tparams[$token[3]], array(null, '', false), true)) {
                                                if ($tparams[$token[3]] && !preg_match('#^'.$token[2].'$#', $tparams[$token[3]])) {
                            $message = sprintf('Parameter "%s" for route "%s" must match "%s" ("%s" given).', $token[3], $name, $token[2], $tparams[$token[3]]);
                            if ($this->strictRequirements) {
                                throw new InvalidParameterException($message);
                            }

                            if ($this->logger) {
                                $this->logger->err($message);
                            }

                            return null;
                        }
                    }

                    if (!$isEmpty || !$optional) {
                        $url = $token[1].$tparams[$token[3]].$url;
                    }

                    $optional = false;
                }
            } elseif ('text' === $token[0]) {
                $url = $token[1].$url;
                $optional = false;
            }
        }

        if ('' === $url) {
            $url = '/';
        }

                $url = $this->context->getBaseUrl().strtr(rawurlencode($url), $this->decodedChars);

                                $url = strtr($url, array('/../' => '/%2E%2E/', '/./' => '/%2E/'));
        if ('/..' === substr($url, -3)) {
            $url = substr($url, 0, -2) . '%2E%2E';
        } elseif ('/.' === substr($url, -2)) {
            $url = substr($url, 0, -1) . '%2E';
        }

                $extra = array_diff_key($originParameters, $variables, $defaults);
        if ($extra && $query = http_build_query($extra, '', '&')) {
            $url .= '?'.$query;
        }

        if ($this->context->getHost()) {
            $scheme = $this->context->getScheme();
            if (isset($requirements['_scheme']) && ($req = strtolower($requirements['_scheme'])) && $scheme != $req) {
                $absolute = true;
                $scheme = $req;
            }

            if ($absolute) {
                $port = '';
                if ('http' === $scheme && 80 != $this->context->getHttpPort()) {
                    $port = ':'.$this->context->getHttpPort();
                } elseif ('https' === $scheme && 443 != $this->context->getHttpsPort()) {
                    $port = ':'.$this->context->getHttpsPort();
                }

                $url = $scheme.'://'.$this->context->getHost().$port.$url;
            }
        }

        return $url;
    }
}
}
 



namespace Symfony\Component\Routing
{

use Symfony\Component\HttpFoundation\Request;


class RequestContext
{
    private $baseUrl;
    private $method;
    private $host;
    private $scheme;
    private $httpPort;
    private $httpsPort;
    private $parameters;

    
    public function __construct($baseUrl = '', $method = 'GET', $host = 'localhost', $scheme = 'http', $httpPort = 80, $httpsPort = 443)
    {
        $this->baseUrl = $baseUrl;
        $this->method = strtoupper($method);
        $this->host = $host;
        $this->scheme = strtolower($scheme);
        $this->httpPort = $httpPort;
        $this->httpsPort = $httpsPort;
        $this->parameters = array();
    }

    public function fromRequest(Request $request)
    {
        $this->setBaseUrl($request->getBaseUrl());
        $this->setMethod($request->getMethod());
        $this->setHost($request->getHost());
        $this->setScheme($request->getScheme());
        $this->setHttpPort($request->isSecure() ? $this->httpPort : $request->getPort());
        $this->setHttpsPort($request->isSecure() ? $request->getPort() : $this->httpsPort);
    }

    
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    
    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    
    public function getMethod()
    {
        return $this->method;
    }

    
    public function setMethod($method)
    {
        $this->method = strtoupper($method);
    }

    
    public function getHost()
    {
        return $this->host;
    }

    
    public function setHost($host)
    {
        $this->host = $host;
    }

    
    public function getScheme()
    {
        return $this->scheme;
    }

    
    public function setScheme($scheme)
    {
        $this->scheme = strtolower($scheme);
    }

    
    public function getHttpPort()
    {
        return $this->httpPort;
    }

    
    public function setHttpPort($httpPort)
    {
        $this->httpPort = $httpPort;
    }

    
    public function getHttpsPort()
    {
        return $this->httpsPort;
    }

    
    public function setHttpsPort($httpsPort)
    {
        $this->httpsPort = $httpsPort;
    }

    
    public function getParameters()
    {
        return $this->parameters;
    }

    
    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }

    
    public function getParameter($name)
    {
        return isset($this->parameters[$name]) ? $this->parameters[$name] : null;
    }

    
    public function hasParameter($name)
    {
        return array_key_exists($name, $this->parameters);
    }

    
    public function setParameter($name, $parameter)
    {
        $this->parameters[$name] = $parameter;
    }
}
}
 



namespace Symfony\Component\Security\Core\Authentication
{

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;


interface AuthenticationManagerInterface
{
    
    public function authenticate(TokenInterface $token);
}
}
 



namespace Symfony\Component\Security\Core\Authentication
{

use Symfony\Component\Security\Core\Event\AuthenticationFailureEvent;
use Symfony\Component\Security\Core\Event\AuthenticationEvent;
use Symfony\Component\Security\Core\AuthenticationEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\ProviderNotFoundException;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;


class AuthenticationProviderManager implements AuthenticationManagerInterface
{
    private $providers;
    private $eraseCredentials;
    private $eventDispatcher;

    
    public function __construct(array $providers, $eraseCredentials = true)
    {
        if (!$providers) {
            throw new \InvalidArgumentException('You must at least add one authentication provider.');
        }

        $this->providers = $providers;
        $this->eraseCredentials = (Boolean) $eraseCredentials;
    }

    public function setEventDispatcher(EventDispatcherInterface $dispatcher)
    {
        $this->eventDispatcher = $dispatcher;
    }

    
    public function authenticate(TokenInterface $token)
    {
        $lastException = null;
        $result = null;

        foreach ($this->providers as $provider) {
            if (!$provider->supports($token)) {
                continue;
            }

            try {
                $result = $provider->authenticate($token);

                if (null !== $result) {
                    break;
                }
            } catch (AccountStatusException $e) {
                $e->setExtraInformation($token);

                throw $e;
            } catch (AuthenticationException $e) {
                $lastException = $e;
            }
        }

        if (null !== $result) {
            if (true === $this->eraseCredentials) {
                $result->eraseCredentials();
            }

            if (null !== $this->eventDispatcher) {
                $this->eventDispatcher->dispatch(AuthenticationEvents::AUTHENTICATION_SUCCESS, new AuthenticationEvent($result));
            }

            return $result;
        }

        if (null === $lastException) {
            $lastException = new ProviderNotFoundException(sprintf('No Authentication Provider found for token of class "%s".', get_class($token)));
        }

        if (null !== $this->eventDispatcher) {
            $this->eventDispatcher->dispatch(AuthenticationEvents::AUTHENTICATION_FAILURE, new AuthenticationFailureEvent($token, $lastException));
        }

        $lastException->setExtraInformation($token);

        throw $lastException;
    }
}
}
 



namespace Symfony\Component\Security\Core\Authorization
{

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;


interface AccessDecisionManagerInterface
{
    
    public function decide(TokenInterface $token, array $attributes, $object = null);

    
    public function supportsAttribute($attribute);

    
    public function supportsClass($class);
}
}
 



namespace Symfony\Component\Security\Core\Authorization
{

use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;


class AccessDecisionManager implements AccessDecisionManagerInterface
{
    private $voters;
    private $strategy;
    private $allowIfAllAbstainDecisions;
    private $allowIfEqualGrantedDeniedDecisions;

    
    public function __construct(array $voters, $strategy = 'affirmative', $allowIfAllAbstainDecisions = false, $allowIfEqualGrantedDeniedDecisions = true)
    {
        if (!$voters) {
            throw new \InvalidArgumentException('You must at least add one voter.');
        }

        $this->voters = $voters;
        $this->strategy = 'decide'.ucfirst($strategy);
        $this->allowIfAllAbstainDecisions = (Boolean) $allowIfAllAbstainDecisions;
        $this->allowIfEqualGrantedDeniedDecisions = (Boolean) $allowIfEqualGrantedDeniedDecisions;
    }

    
    public function decide(TokenInterface $token, array $attributes, $object = null)
    {
        return $this->{$this->strategy}($token, $attributes, $object);
    }

    
    public function supportsAttribute($attribute)
    {
        foreach ($this->voters as $voter) {
            if ($voter->supportsAttribute($attribute)) {
                return true;
            }
        }

        return false;
    }

    
    public function supportsClass($class)
    {
        foreach ($this->voters as $voter) {
            if ($voter->supportsClass($class)) {
                return true;
            }
        }

        return false;
    }

    
    private function decideAffirmative(TokenInterface $token, array $attributes, $object = null)
    {
        $deny = 0;
        foreach ($this->voters as $voter) {
            $result = $voter->vote($token, $object, $attributes);
            switch ($result) {
                case VoterInterface::ACCESS_GRANTED:
                    return true;

                case VoterInterface::ACCESS_DENIED:
                    ++$deny;

                    break;

                default:
                    break;
            }
        }

        if ($deny > 0) {
            return false;
        }

        return $this->allowIfAllAbstainDecisions;
    }

    
    private function decideConsensus(TokenInterface $token, array $attributes, $object = null)
    {
        $grant = 0;
        $deny = 0;
        $abstain = 0;
        foreach ($this->voters as $voter) {
            $result = $voter->vote($token, $object, $attributes);

            switch ($result) {
                case VoterInterface::ACCESS_GRANTED:
                    ++$grant;

                    break;

                case VoterInterface::ACCESS_DENIED:
                    ++$deny;

                    break;

                default:
                    ++$abstain;

                    break;
            }
        }

        if ($grant > $deny) {
            return true;
        }

        if ($deny > $grant) {
            return false;
        }

        if ($grant == $deny && $grant != 0) {
            return $this->allowIfEqualGrantedDeniedDecisions;
        }

        return $this->allowIfAllAbstainDecisions;
    }

    
    private function decideUnanimous(TokenInterface $token, array $attributes, $object = null)
    {
        $grant = 0;
        foreach ($attributes as $attribute) {
            foreach ($this->voters as $voter) {
                $result = $voter->vote($token, $object, array($attribute));

                switch ($result) {
                    case VoterInterface::ACCESS_GRANTED:
                        ++$grant;

                        break;

                    case VoterInterface::ACCESS_DENIED:
                        return false;

                    default:
                        break;
                }
            }
        }

                if ($grant > 0) {
            return true;
        }

        return $this->allowIfAllAbstainDecisions;
    }
}
}
 



namespace Symfony\Component\Security\Core\Authorization\Voter
{

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;


interface VoterInterface
{
    const ACCESS_GRANTED = 1;
    const ACCESS_ABSTAIN = 0;
    const ACCESS_DENIED  = -1;

    
    public function supportsAttribute($attribute);

    
    public function supportsClass($class);

    
    public function vote(TokenInterface $token, $object, array $attributes);
}
}
 



namespace Symfony\Component\Security\Core
{

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;


interface SecurityContextInterface
{
    const ACCESS_DENIED_ERROR  = '_security.403_error';
    const AUTHENTICATION_ERROR = '_security.last_error';
    const LAST_USERNAME        = '_security.last_username';

    
    public function getToken();

    
    public function setToken(TokenInterface $token = null);

    
    public function isGranted($attributes, $object = null);
}
}
 



namespace Symfony\Component\Security\Core
{

use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;


class SecurityContext implements SecurityContextInterface
{
    private $token;
    private $accessDecisionManager;
    private $authenticationManager;
    private $alwaysAuthenticate;

    
    public function __construct(AuthenticationManagerInterface $authenticationManager, AccessDecisionManagerInterface $accessDecisionManager, $alwaysAuthenticate = false)
    {
        $this->authenticationManager = $authenticationManager;
        $this->accessDecisionManager = $accessDecisionManager;
        $this->alwaysAuthenticate = $alwaysAuthenticate;
    }

    
    final public function isGranted($attributes, $object = null)
    {
        if (null === $this->token) {
            throw new AuthenticationCredentialsNotFoundException('The security context contains no authentication token. One possible reason may be that there is no firewall configured for this URL.');
        }

        if ($this->alwaysAuthenticate || !$this->token->isAuthenticated()) {
            $this->token = $this->authenticationManager->authenticate($this->token);
        }

        if (!is_array($attributes)) {
            $attributes = array($attributes);
        }

        return $this->accessDecisionManager->decide($this->token, $attributes, $object);
    }

    
    public function getToken()
    {
        return $this->token;
    }

    
    public function setToken(TokenInterface $token = null)
    {
        $this->token = $token;
    }
}
}
 



namespace Symfony\Component\Security\Core\User
{

use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;


interface UserProviderInterface
{
    
    public function loadUserByUsername($username);

    
    public function refreshUser(UserInterface $user);

    
    public function supportsClass($class);
}
}
 



namespace Symfony\Component\Security\Http
{

use Symfony\Component\HttpFoundation\Request;


interface AccessMapInterface
{
    
    public function getPatterns(Request $request);
}
}
 



namespace Symfony\Component\Security\Http
{

use Symfony\Component\HttpFoundation\RequestMatcherInterface;
use Symfony\Component\HttpFoundation\Request;


class AccessMap implements AccessMapInterface
{
    private $map = array();

    
    public function add(RequestMatcherInterface $requestMatcher, array $roles = array(), $channel = null)
    {
        $this->map[] = array($requestMatcher, $roles, $channel);
    }

    public function getPatterns(Request $request)
    {
        foreach ($this->map as $elements) {
            if (null === $elements[0] || $elements[0]->matches($request)) {
                return array($elements[1], $elements[2]);
            }
        }

        return array(null, null);
    }
}
}
 



namespace Symfony\Component\Security\Http
{

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;


class Firewall
{
    private $map;
    private $dispatcher;

    
    public function __construct(FirewallMapInterface $map, EventDispatcherInterface $dispatcher)
    {
        $this->map = $map;
        $this->dispatcher = $dispatcher;
    }

    
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

                list($listeners, $exception) = $this->map->getListeners($event->getRequest());
        if (null !== $exception) {
            $exception->register($this->dispatcher);
        }

                foreach ($listeners as $listener) {
            $response = $listener->handle($event);

            if ($event->hasResponse()) {
                break;
            }
        }
    }
}
}

namespace
{

/*
 * This file is part of Twig.
 *
 * (c) 2009 Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
/**
 * Stores the Twig configuration.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class Twig_Environment
{
    const VERSION = '1.12.4-DEV';
    protected $charset;
    protected $loader;
    protected $debug;
    protected $autoReload;
    protected $cache;
    protected $lexer;
    protected $parser;
    protected $compiler;
    protected $baseTemplateClass;
    protected $extensions;
    protected $parsers;
    protected $visitors;
    protected $filters;
    protected $tests;
    protected $functions;
    protected $globals;
    protected $runtimeInitialized;
    protected $extensionInitialized;
    protected $loadedTemplates;
    protected $strictVariables;
    protected $unaryOperators;
    protected $binaryOperators;
    protected $templateClassPrefix = '__TwigTemplate_';
    protected $functionCallbacks;
    protected $filterCallbacks;
    protected $staging;
    /**
     * Constructor.
     *
     * Available options:
     *
     *  * debug: When set to true, it automatically set "auto_reload" to true as
     *           well (default to false).
     *
     *  * charset: The charset used by the templates (default to utf-8).
     *
     *  * base_template_class: The base template class to use for generated
     *                         templates (default to Twig_Template).
     *
     *  * cache: An absolute path where to store the compiled templates, or
     *           false to disable compilation cache (default).
     *
     *  * auto_reload: Whether to reload the template is the original source changed.
     *                 If you don't provide the auto_reload option, it will be
     *                 determined automatically base on the debug value.
     *
     *  * strict_variables: Whether to ignore invalid variables in templates
     *                      (default to false).
     *
     *  * autoescape: Whether to enable auto-escaping (default to html):
     *                  * false: disable auto-escaping
     *                  * true: equivalent to html
     *                  * html, js: set the autoescaping to one of the supported strategies
     *                  * PHP callback: a PHP callback that returns an escaping strategy based on the template "filename"
     *
     *  * optimizations: A flag that indicates which optimizations to apply
     *                   (default to -1 which means that all optimizations are enabled;
     *                   set it to 0 to disable).
     *
     * @param Twig_LoaderInterface $loader  A Twig_LoaderInterface instance
     * @param array                $options An array of options
     */
    public function __construct(Twig_LoaderInterface $loader = null, $options = array())
    {
        if (null !== $loader) {
            $this->setLoader($loader);
        }
        $options = array_merge(array(
            'debug'               => false,
            'charset'             => 'UTF-8',
            'base_template_class' => 'Twig_Template',
            'strict_variables'    => false,
            'autoescape'          => 'html',
            'cache'               => false,
            'auto_reload'         => null,
            'optimizations'       => -1,
        ), $options);
        $this->debug              = (bool) $options['debug'];
        $this->charset            = $options['charset'];
        $this->baseTemplateClass  = $options['base_template_class'];
        $this->autoReload         = null === $options['auto_reload'] ? $this->debug : (bool) $options['auto_reload'];
        $this->strictVariables    = (bool) $options['strict_variables'];
        $this->runtimeInitialized = false;
        $this->setCache($options['cache']);
        $this->functionCallbacks = array();
        $this->filterCallbacks = array();
        $this->addExtension(new Twig_Extension_Core());
        $this->addExtension(new Twig_Extension_Escaper($options['autoescape']));
        $this->addExtension(new Twig_Extension_Optimizer($options['optimizations']));
        $this->extensionInitialized = false;
        $this->staging = new Twig_Extension_Staging();
    }
    /**
     * Gets the base template class for compiled templates.
     *
     * @return string The base template class name
     */
    public function getBaseTemplateClass()
    {
        return $this->baseTemplateClass;
    }
    /**
     * Sets the base template class for compiled templates.
     *
     * @param string $class The base template class name
     */
    public function setBaseTemplateClass($class)
    {
        $this->baseTemplateClass = $class;
    }
    /**
     * Enables debugging mode.
     */
    public function enableDebug()
    {
        $this->debug = true;
    }
    /**
     * Disables debugging mode.
     */
    public function disableDebug()
    {
        $this->debug = false;
    }
    /**
     * Checks if debug mode is enabled.
     *
     * @return Boolean true if debug mode is enabled, false otherwise
     */
    public function isDebug()
    {
        return $this->debug;
    }
    /**
     * Enables the auto_reload option.
     */
    public function enableAutoReload()
    {
        $this->autoReload = true;
    }
    /**
     * Disables the auto_reload option.
     */
    public function disableAutoReload()
    {
        $this->autoReload = false;
    }
    /**
     * Checks if the auto_reload option is enabled.
     *
     * @return Boolean true if auto_reload is enabled, false otherwise
     */
    public function isAutoReload()
    {
        return $this->autoReload;
    }
    /**
     * Enables the strict_variables option.
     */
    public function enableStrictVariables()
    {
        $this->strictVariables = true;
    }
    /**
     * Disables the strict_variables option.
     */
    public function disableStrictVariables()
    {
        $this->strictVariables = false;
    }
    /**
     * Checks if the strict_variables option is enabled.
     *
     * @return Boolean true if strict_variables is enabled, false otherwise
     */
    public function isStrictVariables()
    {
        return $this->strictVariables;
    }
    /**
     * Gets the cache directory or false if cache is disabled.
     *
     * @return string|false
     */
    public function getCache()
    {
        return $this->cache;
    }
     /**
      * Sets the cache directory or false if cache is disabled.
      *
      * @param string|false $cache The absolute path to the compiled templates,
      *                            or false to disable cache
      */
    public function setCache($cache)
    {
        $this->cache = $cache ? $cache : false;
    }
    /**
     * Gets the cache filename for a given template.
     *
     * @param string $name The template name
     *
     * @return string The cache file name
     */
    public function getCacheFilename($name)
    {
        if (false === $this->cache) {
            return false;
        }
        $class = substr($this->getTemplateClass($name), strlen($this->templateClassPrefix));
        return $this->getCache().'/'.substr($class, 0, 2).'/'.substr($class, 2, 2).'/'.substr($class, 4).'.php';
    }
    /**
     * Gets the template class associated with the given string.
     *
     * @param string  $name  The name for which to calculate the template class name
     * @param integer $index The index if it is an embedded template
     *
     * @return string The template class name
     */
    public function getTemplateClass($name, $index = null)
    {
        return $this->templateClassPrefix.md5($this->getLoader()->getCacheKey($name)).(null === $index ? '' : '_'.$index);
    }
    /**
     * Gets the template class prefix.
     *
     * @return string The template class prefix
     */
    public function getTemplateClassPrefix()
    {
        return $this->templateClassPrefix;
    }
    /**
     * Renders a template.
     *
     * @param string $name    The template name
     * @param array  $context An array of parameters to pass to the template
     *
     * @return string The rendered template
     */
    public function render($name, array $context = array())
    {
        return $this->loadTemplate($name)->render($context);
    }
    /**
     * Displays a template.
     *
     * @param string $name    The template name
     * @param array  $context An array of parameters to pass to the template
     */
    public function display($name, array $context = array())
    {
        $this->loadTemplate($name)->display($context);
    }
    /**
     * Loads a template by name.
     *
     * @param string  $name  The template name
     * @param integer $index The index if it is an embedded template
     *
     * @return Twig_TemplateInterface A template instance representing the given template name
     */
    public function loadTemplate($name, $index = null)
    {
        $cls = $this->getTemplateClass($name, $index);
        if (isset($this->loadedTemplates[$cls])) {
            return $this->loadedTemplates[$cls];
        }
        if (!class_exists($cls, false)) {
            if (false === $cache = $this->getCacheFilename($name)) {
                eval('?>'.$this->compileSource($this->getLoader()->getSource($name), $name));
            } else {
                if (!is_file($cache) || ($this->isAutoReload() && !$this->isTemplateFresh($name, filemtime($cache)))) {
                    $this->writeCacheFile($cache, $this->compileSource($this->getLoader()->getSource($name), $name));
                }
                require_once $cache;
            }
        }
        if (!$this->runtimeInitialized) {
            $this->initRuntime();
        }
        return $this->loadedTemplates[$cls] = new $cls($this);
    }
    /**
     * Returns true if the template is still fresh.
     *
     * Besides checking the loader for freshness information,
     * this method also checks if the enabled extensions have
     * not changed.
     *
     * @param string    $name The template name
     * @param timestamp $time The last modification time of the cached template
     *
     * @return Boolean true if the template is fresh, false otherwise
     */
    public function isTemplateFresh($name, $time)
    {
        foreach ($this->extensions as $extension) {
            $r = new ReflectionObject($extension);
            if (filemtime($r->getFileName()) > $time) {
                return false;
            }
        }
        return $this->getLoader()->isFresh($name, $time);
    }
    public function resolveTemplate($names)
    {
        if (!is_array($names)) {
            $names = array($names);
        }
        foreach ($names as $name) {
            if ($name instanceof Twig_Template) {
                return $name;
            }
            try {
                return $this->loadTemplate($name);
            } catch (Twig_Error_Loader $e) {
            }
        }
        if (1 === count($names)) {
            throw $e;
        }
        throw new Twig_Error_Loader(sprintf('Unable to find one of the following templates: "%s".', implode('", "', $names)));
    }
    /**
     * Clears the internal template cache.
     */
    public function clearTemplateCache()
    {
        $this->loadedTemplates = array();
    }
    /**
     * Clears the template cache files on the filesystem.
     */
    public function clearCacheFiles()
    {
        if (false === $this->cache) {
            return;
        }
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->cache), RecursiveIteratorIterator::LEAVES_ONLY) as $file) {
            if ($file->isFile()) {
                @unlink($file->getPathname());
            }
        }
    }
    /**
     * Gets the Lexer instance.
     *
     * @return Twig_LexerInterface A Twig_LexerInterface instance
     */
    public function getLexer()
    {
        if (null === $this->lexer) {
            $this->lexer = new Twig_Lexer($this);
        }
        return $this->lexer;
    }
    /**
     * Sets the Lexer instance.
     *
     * @param Twig_LexerInterface A Twig_LexerInterface instance
     */
    public function setLexer(Twig_LexerInterface $lexer)
    {
        $this->lexer = $lexer;
    }
    /**
     * Tokenizes a source code.
     *
     * @param string $source The template source code
     * @param string $name   The template name
     *
     * @return Twig_TokenStream A Twig_TokenStream instance
     */
    public function tokenize($source, $name = null)
    {
        return $this->getLexer()->tokenize($source, $name);
    }
    /**
     * Gets the Parser instance.
     *
     * @return Twig_ParserInterface A Twig_ParserInterface instance
     */
    public function getParser()
    {
        if (null === $this->parser) {
            $this->parser = new Twig_Parser($this);
        }
        return $this->parser;
    }
    /**
     * Sets the Parser instance.
     *
     * @param Twig_ParserInterface A Twig_ParserInterface instance
     */
    public function setParser(Twig_ParserInterface $parser)
    {
        $this->parser = $parser;
    }
    /**
     * Parses a token stream.
     *
     * @param Twig_TokenStream $tokens A Twig_TokenStream instance
     *
     * @return Twig_Node_Module A Node tree
     */
    public function parse(Twig_TokenStream $tokens)
    {
        return $this->getParser()->parse($tokens);
    }
    /**
     * Gets the Compiler instance.
     *
     * @return Twig_CompilerInterface A Twig_CompilerInterface instance
     */
    public function getCompiler()
    {
        if (null === $this->compiler) {
            $this->compiler = new Twig_Compiler($this);
        }
        return $this->compiler;
    }
    /**
     * Sets the Compiler instance.
     *
     * @param Twig_CompilerInterface $compiler A Twig_CompilerInterface instance
     */
    public function setCompiler(Twig_CompilerInterface $compiler)
    {
        $this->compiler = $compiler;
    }
    /**
     * Compiles a Node.
     *
     * @param Twig_NodeInterface $node A Twig_NodeInterface instance
     *
     * @return string The compiled PHP source code
     */
    public function compile(Twig_NodeInterface $node)
    {
        return $this->getCompiler()->compile($node)->getSource();
    }
    /**
     * Compiles a template source code.
     *
     * @param string $source The template source code
     * @param string $name   The template name
     *
     * @return string The compiled PHP source code
     */
    public function compileSource($source, $name = null)
    {
        try {
            return $this->compile($this->parse($this->tokenize($source, $name)));
        } catch (Twig_Error $e) {
            $e->setTemplateFile($name);
            throw $e;
        } catch (Exception $e) {
            throw new Twig_Error_Runtime(sprintf('An exception has been thrown during the compilation of a template ("%s").', $e->getMessage()), -1, $name, $e);
        }
    }
    /**
     * Sets the Loader instance.
     *
     * @param Twig_LoaderInterface $loader A Twig_LoaderInterface instance
     */
    public function setLoader(Twig_LoaderInterface $loader)
    {
        $this->loader = $loader;
    }
    /**
     * Gets the Loader instance.
     *
     * @return Twig_LoaderInterface A Twig_LoaderInterface instance
     */
    public function getLoader()
    {
        if (null === $this->loader) {
            throw new LogicException('You must set a loader first.');
        }
        return $this->loader;
    }
    /**
     * Sets the default template charset.
     *
     * @param string $charset The default charset
     */
    public function setCharset($charset)
    {
        $this->charset = $charset;
    }
    /**
     * Gets the default template charset.
     *
     * @return string The default charset
     */
    public function getCharset()
    {
        return $this->charset;
    }
    /**
     * Initializes the runtime environment.
     */
    public function initRuntime()
    {
        $this->runtimeInitialized = true;
        foreach ($this->getExtensions() as $extension) {
            $extension->initRuntime($this);
        }
    }
    /**
     * Returns true if the given extension is registered.
     *
     * @param string $name The extension name
     *
     * @return Boolean Whether the extension is registered or not
     */
    public function hasExtension($name)
    {
        return isset($this->extensions[$name]);
    }
    /**
     * Gets an extension by name.
     *
     * @param string $name The extension name
     *
     * @return Twig_ExtensionInterface A Twig_ExtensionInterface instance
     */
    public function getExtension($name)
    {
        if (!isset($this->extensions[$name])) {
            throw new Twig_Error_Runtime(sprintf('The "%s" extension is not enabled.', $name));
        }
        return $this->extensions[$name];
    }
    /**
     * Registers an extension.
     *
     * @param Twig_ExtensionInterface $extension A Twig_ExtensionInterface instance
     */
    public function addExtension(Twig_ExtensionInterface $extension)
    {
        if ($this->extensionInitialized) {
            throw new LogicException(sprintf('Unable to register extension "%s" as extensions have already been initialized.', $extension->getName()));
        }
        $this->extensions[$extension->getName()] = $extension;
    }
    /**
     * Removes an extension by name.
     *
     * This method is deprecated and you should not use it.
     *
     * @param string $name The extension name
     *
     * @deprecated since 1.12 (to be removed in 2.0)
     */
    public function removeExtension($name)
    {
        if ($this->extensionInitialized) {
            throw new LogicException(sprintf('Unable to remove extension "%s" as extensions have already been initialized.', $name));
        }
        unset($this->extensions[$name]);
    }
    /**
     * Registers an array of extensions.
     *
     * @param array $extensions An array of extensions
     */
    public function setExtensions(array $extensions)
    {
        foreach ($extensions as $extension) {
            $this->addExtension($extension);
        }
    }
    /**
     * Returns all registered extensions.
     *
     * @return array An array of extensions
     */
    public function getExtensions()
    {
        return $this->extensions;
    }
    /**
     * Registers a Token Parser.
     *
     * @param Twig_TokenParserInterface $parser A Twig_TokenParserInterface instance
     */
    public function addTokenParser(Twig_TokenParserInterface $parser)
    {
        if ($this->extensionInitialized) {
            throw new LogicException('Unable to add a token parser as extensions have already been initialized.');
        }
        $this->staging->addTokenParser($parser);
    }
    /**
     * Gets the registered Token Parsers.
     *
     * @return Twig_TokenParserBrokerInterface A broker containing token parsers
     */
    public function getTokenParsers()
    {
        if (!$this->extensionInitialized) {
            $this->initExtensions();
        }
        return $this->parsers;
    }
    /**
     * Gets registered tags.
     *
     * Be warned that this method cannot return tags defined by Twig_TokenParserBrokerInterface classes.
     *
     * @return Twig_TokenParserInterface[] An array of Twig_TokenParserInterface instances
     */
    public function getTags()
    {
        $tags = array();
        foreach ($this->getTokenParsers()->getParsers() as $parser) {
            if ($parser instanceof Twig_TokenParserInterface) {
                $tags[$parser->getTag()] = $parser;
            }
        }
        return $tags;
    }
    /**
     * Registers a Node Visitor.
     *
     * @param Twig_NodeVisitorInterface $visitor A Twig_NodeVisitorInterface instance
     */
    public function addNodeVisitor(Twig_NodeVisitorInterface $visitor)
    {
        if ($this->extensionInitialized) {
            throw new LogicException('Unable to add a node visitor as extensions have already been initialized.', $extension->getName());
        }
        $this->staging->addNodeVisitor($visitor);
    }
    /**
     * Gets the registered Node Visitors.
     *
     * @return Twig_NodeVisitorInterface[] An array of Twig_NodeVisitorInterface instances
     */
    public function getNodeVisitors()
    {
        if (!$this->extensionInitialized) {
            $this->initExtensions();
        }
        return $this->visitors;
    }
    /**
     * Registers a Filter.
     *
     * @param string|Twig_SimpleFilter               $name   The filter name or a Twig_SimpleFilter instance
     * @param Twig_FilterInterface|Twig_SimpleFilter $filter A Twig_FilterInterface instance or a Twig_SimpleFilter instance
     */
    public function addFilter($name, $filter = null)
    {
        if (!$name instanceof Twig_SimpleFilter && !($filter instanceof Twig_SimpleFilter || $filter instanceof Twig_FilterInterface)) {
            throw new LogicException('A filter must be an instance of Twig_FilterInterface or Twig_SimpleFilter');
        }
        if ($name instanceof Twig_SimpleFilter) {
            $filter = $name;
            $name = $filter->getName();
        }
        if ($this->extensionInitialized) {
            throw new LogicException(sprintf('Unable to add filter "%s" as extensions have already been initialized.', $name));
        }
        $this->staging->addFilter($name, $filter);
    }
    /**
     * Get a filter by name.
     *
     * Subclasses may override this method and load filters differently;
     * so no list of filters is available.
     *
     * @param string $name The filter name
     *
     * @return Twig_Filter|false A Twig_Filter instance or false if the filter does not exist
     */
    public function getFilter($name)
    {
        if (!$this->extensionInitialized) {
            $this->initExtensions();
        }
        if (isset($this->filters[$name])) {
            return $this->filters[$name];
        }
        foreach ($this->filters as $pattern => $filter) {
            $pattern = str_replace('\\*', '(.*?)', preg_quote($pattern, '#'), $count);
            if ($count) {
                if (preg_match('#^'.$pattern.'$#', $name, $matches)) {
                    array_shift($matches);
                    $filter->setArguments($matches);
                    return $filter;
                }
            }
        }
        foreach ($this->filterCallbacks as $callback) {
            if (false !== $filter = call_user_func($callback, $name)) {
                return $filter;
            }
        }
        return false;
    }
    public function registerUndefinedFilterCallback($callable)
    {
        $this->filterCallbacks[] = $callable;
    }
    /**
     * Gets the registered Filters.
     *
     * Be warned that this method cannot return filters defined with registerUndefinedFunctionCallback.
     *
     * @return Twig_FilterInterface[] An array of Twig_FilterInterface instances
     *
     * @see registerUndefinedFilterCallback
     */
    public function getFilters()
    {
        if (!$this->extensionInitialized) {
            $this->initExtensions();
        }
        return $this->filters;
    }
    /**
     * Registers a Test.
     *
     * @param string|Twig_SimpleTest             $name The test name or a Twig_SimpleTest instance
     * @param Twig_TestInterface|Twig_SimpleTest $test A Twig_TestInterface instance or a Twig_SimpleTest instance
     */
    public function addTest($name, $test = null)
    {
        if (!$name instanceof Twig_SimpleTest && !($test instanceof Twig_SimpleTest || $test instanceof Twig_TestInterface)) {
            throw new LogicException('A test must be an instance of Twig_TestInterface or Twig_SimpleTest');
        }
        if ($name instanceof Twig_SimpleTest) {
            $test = $name;
            $name = $test->getName();
        }
        if ($this->extensionInitialized) {
            throw new LogicException(sprintf('Unable to add test "%s" as extensions have already been initialized.', $name));
        }
        $this->staging->addTest($name, $test);
    }
    /**
     * Gets the registered Tests.
     *
     * @return Twig_TestInterface[] An array of Twig_TestInterface instances
     */
    public function getTests()
    {
        if (!$this->extensionInitialized) {
            $this->initExtensions();
        }
        return $this->tests;
    }
    /**
     * Gets a test by name.
     *
     * @param string $name The test name
     *
     * @return Twig_Test|false A Twig_Test instance or false if the test does not exist
     */
    public function getTest($name)
    {
        if (!$this->extensionInitialized) {
            $this->initExtensions();
        }
        if (isset($this->tests[$name])) {
            return $this->tests[$name];
        }
        return false;
    }
    /**
     * Registers a Function.
     *
     * @param string|Twig_SimpleFunction                 $name     The function name or a Twig_SimpleFunction instance
     * @param Twig_FunctionInterface|Twig_SimpleFunction $function A Twig_FunctionInterface instance or a Twig_SimpleFunction instance
     */
    public function addFunction($name, $function = null)
    {
        if (!$name instanceof Twig_SimpleFunction && !($function instanceof Twig_SimpleFunction || $function instanceof Twig_FunctionInterface)) {
            throw new LogicException('A function must be an instance of Twig_FunctionInterface or Twig_SimpleFunction');
        }
        if ($name instanceof Twig_SimpleFunction) {
            $function = $name;
            $name = $function->getName();
        }
        if ($this->extensionInitialized) {
            throw new LogicException(sprintf('Unable to add function "%s" as extensions have already been initialized.', $name));
        }
        $this->staging->addFunction($name, $function);
    }
    /**
     * Get a function by name.
     *
     * Subclasses may override this method and load functions differently;
     * so no list of functions is available.
     *
     * @param string $name function name
     *
     * @return Twig_Function|false A Twig_Function instance or false if the function does not exist
     */
    public function getFunction($name)
    {
        if (!$this->extensionInitialized) {
            $this->initExtensions();
        }
        if (isset($this->functions[$name])) {
            return $this->functions[$name];
        }
        foreach ($this->functions as $pattern => $function) {
            $pattern = str_replace('\\*', '(.*?)', preg_quote($pattern, '#'), $count);
            if ($count) {
                if (preg_match('#^'.$pattern.'$#', $name, $matches)) {
                    array_shift($matches);
                    $function->setArguments($matches);
                    return $function;
                }
            }
        }
        foreach ($this->functionCallbacks as $callback) {
            if (false !== $function = call_user_func($callback, $name)) {
                return $function;
            }
        }
        return false;
    }
    public function registerUndefinedFunctionCallback($callable)
    {
        $this->functionCallbacks[] = $callable;
    }
    /**
     * Gets registered functions.
     *
     * Be warned that this method cannot return functions defined with registerUndefinedFunctionCallback.
     *
     * @return Twig_FunctionInterface[] An array of Twig_FunctionInterface instances
     *
     * @see registerUndefinedFunctionCallback
     */
    public function getFunctions()
    {
        if (!$this->extensionInitialized) {
            $this->initExtensions();
        }
        return $this->functions;
    }
    /**
     * Registers a Global.
     *
     * New globals can be added before compiling or rendering a template;
     * but after, you can only update existing globals.
     *
     * @param string $name  The global name
     * @param mixed  $value The global value
     */
    public function addGlobal($name, $value)
    {
        if ($this->extensionInitialized || $this->runtimeInitialized) {
            if (null === $this->globals) {
                $this->globals = $this->initGlobals();
            }
            /* This condition must be uncommented in Twig 2.0
            if (!array_key_exists($name, $this->globals)) {
                throw new LogicException(sprintf('Unable to add global "%s" as the runtime or the extensions have already been initialized.', $name));
            }
            */
        }
        if ($this->extensionInitialized || $this->runtimeInitialized) {
            // update the value
            $this->globals[$name] = $value;
        } else {
            $this->staging->addGlobal($name, $value);
        }
    }
    /**
     * Gets the registered Globals.
     *
     * @return array An array of globals
     */
    public function getGlobals()
    {
        if (!$this->runtimeInitialized && !$this->extensionInitialized) {
            return $this->initGlobals();
        }
        if (null === $this->globals) {
            $this->globals = $this->initGlobals();
        }
        return $this->globals;
    }
    /**
     * Merges a context with the defined globals.
     *
     * @param array $context An array representing the context
     *
     * @return array The context merged with the globals
     */
    public function mergeGlobals(array $context)
    {
        // we don't use array_merge as the context being generally
        // bigger than globals, this code is faster.
        foreach ($this->getGlobals() as $key => $value) {
            if (!array_key_exists($key, $context)) {
                $context[$key] = $value;
            }
        }
        return $context;
    }
    /**
     * Gets the registered unary Operators.
     *
     * @return array An array of unary operators
     */
    public function getUnaryOperators()
    {
        if (!$this->extensionInitialized) {
            $this->initExtensions();
        }
        return $this->unaryOperators;
    }
    /**
     * Gets the registered binary Operators.
     *
     * @return array An array of binary operators
     */
    public function getBinaryOperators()
    {
        if (!$this->extensionInitialized) {
            $this->initExtensions();
        }
        return $this->binaryOperators;
    }
    public function computeAlternatives($name, $items)
    {
        $alternatives = array();
        foreach ($items as $item) {
            $lev = levenshtein($name, $item);
            if ($lev <= strlen($name) / 3 || false !== strpos($item, $name)) {
                $alternatives[$item] = $lev;
            }
        }
        asort($alternatives);
        return array_keys($alternatives);
    }
    protected function initGlobals()
    {
        $globals = array();
        foreach ($this->extensions as $extension) {
            $globals = array_merge($globals, $extension->getGlobals());
        }
        return array_merge($globals, $this->staging->getGlobals());
    }
    protected function initExtensions()
    {
        if ($this->extensionInitialized) {
            return;
        }
        $this->extensionInitialized = true;
        $this->parsers = new Twig_TokenParserBroker();
        $this->filters = array();
        $this->functions = array();
        $this->tests = array();
        $this->visitors = array();
        $this->unaryOperators = array();
        $this->binaryOperators = array();
        foreach ($this->extensions as $extension) {
            $this->initExtension($extension);
        }
        $this->initExtension($this->staging);
    }
    protected function initExtension(Twig_ExtensionInterface $extension)
    {
        // filters
        foreach ($extension->getFilters() as $name => $filter) {
            if ($name instanceof Twig_SimpleFilter) {
                $filter = $name;
                $name = $filter->getName();
            } elseif ($filter instanceof Twig_SimpleFilter) {
                $name = $filter->getName();
            }
            $this->filters[$name] = $filter;
        }
        // functions
        foreach ($extension->getFunctions() as $name => $function) {
            if ($name instanceof Twig_SimpleFunction) {
                $function = $name;
                $name = $function->getName();
            } elseif ($function instanceof Twig_SimpleFunction) {
                $name = $function->getName();
            }
            $this->functions[$name] = $function;
        }
        // tests
        foreach ($extension->getTests() as $name => $test) {
            if ($name instanceof Twig_SimpleTest) {
                $test = $name;
                $name = $test->getName();
            } elseif ($test instanceof Twig_SimpleTest) {
                $name = $test->getName();
            }
            $this->tests[$name] = $test;
        }
        // token parsers
        foreach ($extension->getTokenParsers() as $parser) {
            if ($parser instanceof Twig_TokenParserInterface) {
                $this->parsers->addTokenParser($parser);
            } elseif ($parser instanceof Twig_TokenParserBrokerInterface) {
                $this->parsers->addTokenParserBroker($parser);
            } else {
                throw new LogicException('getTokenParsers() must return an array of Twig_TokenParserInterface or Twig_TokenParserBrokerInterface instances');
            }
        }
        // node visitors
        foreach ($extension->getNodeVisitors() as $visitor) {
            $this->visitors[] = $visitor;
        }
        // operators
        if ($operators = $extension->getOperators()) {
            if (2 !== count($operators)) {
                throw new InvalidArgumentException(sprintf('"%s::getOperators()" does not return a valid operators array.', get_class($extension)));
            }
            $this->unaryOperators = array_merge($this->unaryOperators, $operators[0]);
            $this->binaryOperators = array_merge($this->binaryOperators, $operators[1]);
        }
    }
    protected function writeCacheFile($file, $content)
    {
        $dir = dirname($file);
        if (!is_dir($dir)) {
            if (false === @mkdir($dir, 0777, true) && !is_dir($dir)) {
                throw new RuntimeException(sprintf("Unable to create the cache directory (%s).", $dir));
            }
        } elseif (!is_writable($dir)) {
            throw new RuntimeException(sprintf("Unable to write in the cache directory (%s).", $dir));
        }
        $tmpFile = tempnam(dirname($file), basename($file));
        if (false !== @file_put_contents($tmpFile, $content)) {
            // rename does not work on Win32 before 5.2.6
            if (@rename($tmpFile, $file) || (@copy($tmpFile, $file) && unlink($tmpFile))) {
                @chmod($file, 0666 & ~umask());
                return;
            }
        }
        throw new RuntimeException(sprintf('Failed to write cache file "%s".', $file));
    }
}

}

namespace
{

if (!defined('ENT_SUBSTITUTE')) {
    define('ENT_SUBSTITUTE', 8);
}
/*
 * This file is part of Twig.
 *
 * (c) 2009 Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class Twig_Extension_Core extends Twig_Extension
{
    protected $dateFormats = array('F j, Y H:i', '%d days');
    protected $numberFormat = array(0, '.', ',');
    protected $timezone = null;
    /**
     * Sets the default format to be used by the date filter.
     *
     * @param string $format             The default date format string
     * @param string $dateIntervalFormat The default date interval format string
     */
    public function setDateFormat($format = null, $dateIntervalFormat = null)
    {
        if (null !== $format) {
            $this->dateFormats[0] = $format;
        }
        if (null !== $dateIntervalFormat) {
            $this->dateFormats[1] = $dateIntervalFormat;
        }
    }
    /**
     * Gets the default format to be used by the date filter.
     *
     * @return array The default date format string and the default date interval format string
     */
    public function getDateFormat()
    {
        return $this->dateFormats;
    }
    /**
     * Sets the default timezone to be used by the date filter.
     *
     * @param DateTimeZone|string $timezone The default timezone string or a DateTimeZone object
     */
    public function setTimezone($timezone)
    {
        $this->timezone = $timezone instanceof DateTimeZone ? $timezone : new DateTimeZone($timezone);
    }
    /**
     * Gets the default timezone to be used by the date filter.
     *
     * @return DateTimeZone The default timezone currently in use
     */
    public function getTimezone()
    {
        if (null === $this->timezone) {
            $this->timezone = new DateTimeZone(date_default_timezone_get());
        }
        return $this->timezone;
    }
    /**
     * Sets the default format to be used by the number_format filter.
     *
     * @param integer $decimal      The number of decimal places to use.
     * @param string  $decimalPoint The character(s) to use for the decimal point.
     * @param string  $thousandSep  The character(s) to use for the thousands separator.
     */
    public function setNumberFormat($decimal, $decimalPoint, $thousandSep)
    {
        $this->numberFormat = array($decimal, $decimalPoint, $thousandSep);
    }
    /**
     * Get the default format used by the number_format filter.
     *
     * @return array The arguments for number_format()
     */
    public function getNumberFormat()
    {
        return $this->numberFormat;
    }
    /**
     * Returns the token parser instance to add to the existing list.
     *
     * @return array An array of Twig_TokenParser instances
     */
    public function getTokenParsers()
    {
        return array(
            new Twig_TokenParser_For(),
            new Twig_TokenParser_If(),
            new Twig_TokenParser_Extends(),
            new Twig_TokenParser_Include(),
            new Twig_TokenParser_Block(),
            new Twig_TokenParser_Use(),
            new Twig_TokenParser_Filter(),
            new Twig_TokenParser_Macro(),
            new Twig_TokenParser_Import(),
            new Twig_TokenParser_From(),
            new Twig_TokenParser_Set(),
            new Twig_TokenParser_Spaceless(),
            new Twig_TokenParser_Flush(),
            new Twig_TokenParser_Do(),
            new Twig_TokenParser_Embed(),
        );
    }
    /**
     * Returns a list of filters to add to the existing list.
     *
     * @return array An array of filters
     */
    public function getFilters()
    {
        $filters = array(
            // formatting filters
            new Twig_SimpleFilter('date', 'twig_date_format_filter', array('needs_environment' => true)),
            new Twig_SimpleFilter('date_modify', 'twig_date_modify_filter', array('needs_environment' => true)),
            new Twig_SimpleFilter('format', 'sprintf'),
            new Twig_SimpleFilter('replace', 'strtr'),
            new Twig_SimpleFilter('number_format', 'twig_number_format_filter', array('needs_environment' => true)),
            new Twig_SimpleFilter('abs', 'abs'),
            // encoding
            new Twig_SimpleFilter('url_encode', 'twig_urlencode_filter'),
            new Twig_SimpleFilter('json_encode', 'twig_jsonencode_filter'),
            new Twig_SimpleFilter('convert_encoding', 'twig_convert_encoding'),
            // string filters
            new Twig_SimpleFilter('title', 'twig_title_string_filter', array('needs_environment' => true)),
            new Twig_SimpleFilter('capitalize', 'twig_capitalize_string_filter', array('needs_environment' => true)),
            new Twig_SimpleFilter('upper', 'strtoupper'),
            new Twig_SimpleFilter('lower', 'strtolower'),
            new Twig_SimpleFilter('striptags', 'strip_tags'),
            new Twig_SimpleFilter('trim', 'trim'),
            new Twig_SimpleFilter('nl2br', 'nl2br', array('pre_escape' => 'html', 'is_safe' => array('html'))),
            // array helpers
            new Twig_SimpleFilter('join', 'twig_join_filter'),
            new Twig_SimpleFilter('split', 'twig_split_filter'),
            new Twig_SimpleFilter('sort', 'twig_sort_filter'),
            new Twig_SimpleFilter('merge', 'twig_array_merge'),
            new Twig_SimpleFilter('batch', 'twig_array_batch'),
            // string/array filters
            new Twig_SimpleFilter('reverse', 'twig_reverse_filter', array('needs_environment' => true)),
            new Twig_SimpleFilter('length', 'twig_length_filter', array('needs_environment' => true)),
            new Twig_SimpleFilter('slice', 'twig_slice', array('needs_environment' => true)),
            new Twig_SimpleFilter('first', 'twig_first', array('needs_environment' => true)),
            new Twig_SimpleFilter('last', 'twig_last', array('needs_environment' => true)),
            // iteration and runtime
            new Twig_SimpleFilter('default', '_twig_default_filter', array('node_class' => 'Twig_Node_Expression_Filter_Default')),
            new Twig_SimpleFilter('keys', 'twig_get_array_keys_filter'),
            // escaping
            new Twig_SimpleFilter('escape', 'twig_escape_filter', array('needs_environment' => true, 'is_safe_callback' => 'twig_escape_filter_is_safe')),
            new Twig_SimpleFilter('e', 'twig_escape_filter', array('needs_environment' => true, 'is_safe_callback' => 'twig_escape_filter_is_safe')),
        );
        if (function_exists('mb_get_info')) {
            $filters[] = new Twig_SimpleFilter('upper', 'twig_upper_filter', array('needs_environment' => true));
            $filters[] = new Twig_SimpleFilter('lower', 'twig_lower_filter', array('needs_environment' => true));
        }
        return $filters;
    }
    /**
     * Returns a list of global functions to add to the existing list.
     *
     * @return array An array of global functions
     */
    public function getFunctions()
    {
        return array(
            new Twig_SimpleFunction('range', 'range'),
            new Twig_SimpleFunction('constant', 'twig_constant'),
            new Twig_SimpleFunction('cycle', 'twig_cycle'),
            new Twig_SimpleFunction('random', 'twig_random', array('needs_environment' => true)),
            new Twig_SimpleFunction('date', 'twig_date_converter', array('needs_environment' => true)),
            new Twig_SimpleFunction('include', 'twig_include', array('needs_environment' => true, 'needs_context' => true)),
        );
    }
    /**
     * Returns a list of tests to add to the existing list.
     *
     * @return array An array of tests
     */
    public function getTests()
    {
        return array(
            new Twig_SimpleTest('even', null, array('node_class' => 'Twig_Node_Expression_Test_Even')),
            new Twig_SimpleTest('odd', null, array('node_class' => 'Twig_Node_Expression_Test_Odd')),
            new Twig_SimpleTest('defined', null, array('node_class' => 'Twig_Node_Expression_Test_Defined')),
            new Twig_SimpleTest('sameas', null, array('node_class' => 'Twig_Node_Expression_Test_Sameas')),
            new Twig_SimpleTest('none', null, array('node_class' => 'Twig_Node_Expression_Test_Null')),
            new Twig_SimpleTest('null', null, array('node_class' => 'Twig_Node_Expression_Test_Null')),
            new Twig_SimpleTest('divisibleby', null, array('node_class' => 'Twig_Node_Expression_Test_Divisibleby')),
            new Twig_SimpleTest('constant', null, array('node_class' => 'Twig_Node_Expression_Test_Constant')),
            new Twig_SimpleTest('empty', 'twig_test_empty'),
            new Twig_SimpleTest('iterable', 'twig_test_iterable'),
        );
    }
    /**
     * Returns a list of operators to add to the existing list.
     *
     * @return array An array of operators
     */
    public function getOperators()
    {
        return array(
            array(
                'not' => array('precedence' => 50, 'class' => 'Twig_Node_Expression_Unary_Not'),
                '-'   => array('precedence' => 500, 'class' => 'Twig_Node_Expression_Unary_Neg'),
                '+'   => array('precedence' => 500, 'class' => 'Twig_Node_Expression_Unary_Pos'),
            ),
            array(
                'or'     => array('precedence' => 10, 'class' => 'Twig_Node_Expression_Binary_Or', 'associativity' => Twig_ExpressionParser::OPERATOR_LEFT),
                'and'    => array('precedence' => 15, 'class' => 'Twig_Node_Expression_Binary_And', 'associativity' => Twig_ExpressionParser::OPERATOR_LEFT),
                'b-or'   => array('precedence' => 16, 'class' => 'Twig_Node_Expression_Binary_BitwiseOr', 'associativity' => Twig_ExpressionParser::OPERATOR_LEFT),
                'b-xor'  => array('precedence' => 17, 'class' => 'Twig_Node_Expression_Binary_BitwiseXor', 'associativity' => Twig_ExpressionParser::OPERATOR_LEFT),
                'b-and'  => array('precedence' => 18, 'class' => 'Twig_Node_Expression_Binary_BitwiseAnd', 'associativity' => Twig_ExpressionParser::OPERATOR_LEFT),
                '=='     => array('precedence' => 20, 'class' => 'Twig_Node_Expression_Binary_Equal', 'associativity' => Twig_ExpressionParser::OPERATOR_LEFT),
                '!='     => array('precedence' => 20, 'class' => 'Twig_Node_Expression_Binary_NotEqual', 'associativity' => Twig_ExpressionParser::OPERATOR_LEFT),
                '<'      => array('precedence' => 20, 'class' => 'Twig_Node_Expression_Binary_Less', 'associativity' => Twig_ExpressionParser::OPERATOR_LEFT),
                '>'      => array('precedence' => 20, 'class' => 'Twig_Node_Expression_Binary_Greater', 'associativity' => Twig_ExpressionParser::OPERATOR_LEFT),
                '>='     => array('precedence' => 20, 'class' => 'Twig_Node_Expression_Binary_GreaterEqual', 'associativity' => Twig_ExpressionParser::OPERATOR_LEFT),
                '<='     => array('precedence' => 20, 'class' => 'Twig_Node_Expression_Binary_LessEqual', 'associativity' => Twig_ExpressionParser::OPERATOR_LEFT),
                'not in' => array('precedence' => 20, 'class' => 'Twig_Node_Expression_Binary_NotIn', 'associativity' => Twig_ExpressionParser::OPERATOR_LEFT),
                'in'     => array('precedence' => 20, 'class' => 'Twig_Node_Expression_Binary_In', 'associativity' => Twig_ExpressionParser::OPERATOR_LEFT),
                '..'     => array('precedence' => 25, 'class' => 'Twig_Node_Expression_Binary_Range', 'associativity' => Twig_ExpressionParser::OPERATOR_LEFT),
                '+'      => array('precedence' => 30, 'class' => 'Twig_Node_Expression_Binary_Add', 'associativity' => Twig_ExpressionParser::OPERATOR_LEFT),
                '-'      => array('precedence' => 30, 'class' => 'Twig_Node_Expression_Binary_Sub', 'associativity' => Twig_ExpressionParser::OPERATOR_LEFT),
                '~'      => array('precedence' => 40, 'class' => 'Twig_Node_Expression_Binary_Concat', 'associativity' => Twig_ExpressionParser::OPERATOR_LEFT),
                '*'      => array('precedence' => 60, 'class' => 'Twig_Node_Expression_Binary_Mul', 'associativity' => Twig_ExpressionParser::OPERATOR_LEFT),
                '/'      => array('precedence' => 60, 'class' => 'Twig_Node_Expression_Binary_Div', 'associativity' => Twig_ExpressionParser::OPERATOR_LEFT),
                '//'     => array('precedence' => 60, 'class' => 'Twig_Node_Expression_Binary_FloorDiv', 'associativity' => Twig_ExpressionParser::OPERATOR_LEFT),
                '%'      => array('precedence' => 60, 'class' => 'Twig_Node_Expression_Binary_Mod', 'associativity' => Twig_ExpressionParser::OPERATOR_LEFT),
                'is'     => array('precedence' => 100, 'callable' => array($this, 'parseTestExpression'), 'associativity' => Twig_ExpressionParser::OPERATOR_LEFT),
                'is not' => array('precedence' => 100, 'callable' => array($this, 'parseNotTestExpression'), 'associativity' => Twig_ExpressionParser::OPERATOR_LEFT),
                '**'     => array('precedence' => 200, 'class' => 'Twig_Node_Expression_Binary_Power', 'associativity' => Twig_ExpressionParser::OPERATOR_RIGHT),
            ),
        );
    }
    public function parseNotTestExpression(Twig_Parser $parser, $node)
    {
        return new Twig_Node_Expression_Unary_Not($this->parseTestExpression($parser, $node), $parser->getCurrentToken()->getLine());
    }
    public function parseTestExpression(Twig_Parser $parser, $node)
    {
        $stream = $parser->getStream();
        $name = $stream->expect(Twig_Token::NAME_TYPE)->getValue();
        $arguments = null;
        if ($stream->test(Twig_Token::PUNCTUATION_TYPE, '(')) {
            $arguments = $parser->getExpressionParser()->parseArguments(true);
        }
        $class = $this->getTestNodeClass($parser, $name, $node->getLine());
        return new $class($node, $name, $arguments, $parser->getCurrentToken()->getLine());
    }
    protected function getTestNodeClass(Twig_Parser $parser, $name, $line)
    {
        $env = $parser->getEnvironment();
        $testMap = $env->getTests();
        if (!isset($testMap[$name])) {
            $message = sprintf('The test "%s" does not exist', $name);
            if ($alternatives = $env->computeAlternatives($name, array_keys($env->getTests()))) {
                $message = sprintf('%s. Did you mean "%s"', $message, implode('", "', $alternatives));
            }
            throw new Twig_Error_Syntax($message, $line, $parser->getFilename());
        }
        if ($testMap[$name] instanceof Twig_SimpleTest) {
            return $testMap[$name]->getNodeClass();
        }
        return $testMap[$name] instanceof Twig_Test_Node ? $testMap[$name]->getClass() : 'Twig_Node_Expression_Test';
    }
    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'core';
    }
}
/**
 * Cycles over a value.
 *
 * @param ArrayAccess|array $values   An array or an ArrayAccess instance
 * @param integer           $position The cycle position
 *
 * @return string The next value in the cycle
 */
function twig_cycle($values, $position)
{
    if (!is_array($values) && !$values instanceof ArrayAccess) {
        return $values;
    }
    return $values[$position % count($values)];
}
/**
 * Returns a random value depending on the supplied parameter type:
 * - a random item from a Traversable or array
 * - a random character from a string
 * - a random integer between 0 and the integer parameter
 *
 * @param Twig_Environment                 $env    A Twig_Environment instance
 * @param Traversable|array|integer|string $values The values to pick a random item from
 *
 * @throws Twig_Error_Runtime When $values is an empty array (does not apply to an empty string which is returned as is).
 *
 * @return mixed A random value from the given sequence
 */
function twig_random(Twig_Environment $env, $values = null)
{
    if (null === $values) {
        return mt_rand();
    }
    if (is_int($values) || is_float($values)) {
        return $values < 0 ? mt_rand($values, 0) : mt_rand(0, $values);
    }
    if ($values instanceof Traversable) {
        $values = iterator_to_array($values);
    } elseif (is_string($values)) {
        if ('' === $values) {
            return '';
        }
        if (null !== $charset = $env->getCharset()) {
            if ('UTF-8' != $charset) {
                $values = twig_convert_encoding($values, 'UTF-8', $charset);
            }
            // unicode version of str_split()
            // split at all positions, but not after the start and not before the end
            $values = preg_split('/(?<!^)(?!$)/u', $values);
            if ('UTF-8' != $charset) {
                foreach ($values as $i => $value) {
                    $values[$i] = twig_convert_encoding($value, $charset, 'UTF-8');
                }
            }
        } else {
            return $values[mt_rand(0, strlen($values) - 1)];
        }
    }
    if (!is_array($values)) {
        return $values;
    }
    if (0 === count($values)) {
        throw new Twig_Error_Runtime('The random function cannot pick from an empty array.');
    }
    return $values[array_rand($values, 1)];
}
/**
 * Converts a date to the given format.
 *
 * <pre>
 *   {{ post.published_at|date("m/d/Y") }}
 * </pre>
 *
 * @param Twig_Environment             $env      A Twig_Environment instance
 * @param DateTime|DateInterval|string $date     A date
 * @param string                       $format   A format
 * @param DateTimeZone|string          $timezone A timezone
 *
 * @return string The formatted date
 */
function twig_date_format_filter(Twig_Environment $env, $date, $format = null, $timezone = null)
{
    if (null === $format) {
        $formats = $env->getExtension('core')->getDateFormat();
        $format = $date instanceof DateInterval ? $formats[1] : $formats[0];
    }
    if ($date instanceof DateInterval) {
        return $date->format($format);
    }
    return twig_date_converter($env, $date, $timezone)->format($format);
}
/**
 * Returns a new date object modified
 *
 * <pre>
 *   {{ post.published_at|date_modify("-1day")|date("m/d/Y") }}
 * </pre>
 *
 * @param Twig_Environment  $env      A Twig_Environment instance
 * @param DateTime|string   $date     A date
 * @param string            $modifier A modifier string
 *
 * @return DateTime A new date object
 */
function twig_date_modify_filter(Twig_Environment $env, $date, $modifier)
{
    $date = twig_date_converter($env, $date, false);
    $date->modify($modifier);
    return $date;
}
/**
 * Converts an input to a DateTime instance.
 *
 * <pre>
 *    {% if date(user.created_at) < date('+2days') %}
 *      {# do something #}
 *    {% endif %}
 * </pre>
 *
 * @param Twig_Environment    $env      A Twig_Environment instance
 * @param DateTime|string     $date     A date
 * @param DateTimeZone|string $timezone A timezone
 *
 * @return DateTime A DateTime instance
 */
function twig_date_converter(Twig_Environment $env, $date = null, $timezone = null)
{
    // determine the timezone
    if (!$timezone) {
        $defaultTimezone = $env->getExtension('core')->getTimezone();
    } elseif (!$timezone instanceof DateTimeZone) {
        $defaultTimezone = new DateTimeZone($timezone);
    } else {
        $defaultTimezone = $timezone;
    }
    if ($date instanceof DateTime) {
        $date = clone $date;
        if (false !== $timezone) {
            $date->setTimezone($defaultTimezone);
        }
        return $date;
    }
    $asString = (string) $date;
    if (ctype_digit($asString) || (!empty($asString) && '-' === $asString[0] && ctype_digit(substr($asString, 1)))) {
        $date = '@'.$date;
    }
    $date = new DateTime($date, $defaultTimezone);
    if (false !== $timezone) {
        $date->setTimezone($defaultTimezone);
    }
    return $date;
}
/**
 * Number format filter.
 *
 * All of the formatting options can be left null, in that case the defaults will
 * be used.  Supplying any of the parameters will override the defaults set in the
 * environment object.
 *
 * @param Twig_Environment    $env          A Twig_Environment instance
 * @param mixed               $number       A float/int/string of the number to format
 * @param integer             $decimal      The number of decimal points to display.
 * @param string              $decimalPoint The character(s) to use for the decimal point.
 * @param string              $thousandSep  The character(s) to use for the thousands separator.
 *
 * @return string The formatted number
 */
function twig_number_format_filter(Twig_Environment $env, $number, $decimal = null, $decimalPoint = null, $thousandSep = null)
{
    $defaults = $env->getExtension('core')->getNumberFormat();
    if (null === $decimal) {
        $decimal = $defaults[0];
    }
    if (null === $decimalPoint) {
        $decimalPoint = $defaults[1];
    }
    if (null === $thousandSep) {
        $thousandSep = $defaults[2];
    }
    return number_format((float) $number, $decimal, $decimalPoint, $thousandSep);
}
/**
 * URL encodes a string as a path segment or an array as a query string.
 *
 * @param string|array $url A URL or an array of query parameters
 * @param bool         $raw true to use rawurlencode() instead of urlencode
 *
 * @return string The URL encoded value
 */
function twig_urlencode_filter($url, $raw = false)
{
    if (is_array($url)) {
        return http_build_query($url, '', '&');
    }
    if ($raw) {
        return rawurlencode($url);
    }
    return urlencode($url);
}
if (version_compare(PHP_VERSION, '5.3.0', '<')) {
    /**
     * JSON encodes a variable.
     *
     * @param mixed   $value   The value to encode.
     * @param integer $options Not used on PHP 5.2.x
     *
     * @return mixed The JSON encoded value
     */
    function twig_jsonencode_filter($value, $options = 0)
    {
        if ($value instanceof Twig_Markup) {
            $value = (string) $value;
        } elseif (is_array($value)) {
            array_walk_recursive($value, '_twig_markup2string');
        }
        return json_encode($value);
    }
} else {
    /**
     * JSON encodes a variable.
     *
     * @param mixed   $value   The value to encode.
     * @param integer $options Bitmask consisting of JSON_HEX_QUOT, JSON_HEX_TAG, JSON_HEX_AMP, JSON_HEX_APOS, JSON_NUMERIC_CHECK, JSON_PRETTY_PRINT, JSON_UNESCAPED_SLASHES, JSON_FORCE_OBJECT
     *
     * @return mixed The JSON encoded value
     */
    function twig_jsonencode_filter($value, $options = 0)
    {
        if ($value instanceof Twig_Markup) {
            $value = (string) $value;
        } elseif (is_array($value)) {
            array_walk_recursive($value, '_twig_markup2string');
        }
        return json_encode($value, $options);
    }
}
function _twig_markup2string(&$value)
{
    if ($value instanceof Twig_Markup) {
        $value = (string) $value;
    }
}
/**
 * Merges an array with another one.
 *
 * <pre>
 *  {% set items = { 'apple': 'fruit', 'orange': 'fruit' } %}
 *
 *  {% set items = items|merge({ 'peugeot': 'car' }) %}
 *
 *  {# items now contains { 'apple': 'fruit', 'orange': 'fruit', 'peugeot': 'car' } #}
 * </pre>
 *
 * @param array $arr1 An array
 * @param array $arr2 An array
 *
 * @return array The merged array
 */
function twig_array_merge($arr1, $arr2)
{
    if (!is_array($arr1) || !is_array($arr2)) {
        throw new Twig_Error_Runtime('The merge filter only works with arrays or hashes.');
    }
    return array_merge($arr1, $arr2);
}
/**
 * Slices a variable.
 *
 * @param Twig_Environment $env          A Twig_Environment instance
 * @param mixed            $item         A variable
 * @param integer          $start        Start of the slice
 * @param integer          $length       Size of the slice
 * @param Boolean          $preserveKeys Whether to preserve key or not (when the input is an array)
 *
 * @return mixed The sliced variable
 */
function twig_slice(Twig_Environment $env, $item, $start, $length = null, $preserveKeys = false)
{
    if ($item instanceof Traversable) {
        $item = iterator_to_array($item, false);
    }
    if (is_array($item)) {
        return array_slice($item, $start, $length, $preserveKeys);
    }
    $item = (string) $item;
    if (function_exists('mb_get_info') && null !== $charset = $env->getCharset()) {
        return mb_substr($item, $start, null === $length ? mb_strlen($item, $charset) - $start : $length, $charset);
    }
    return null === $length ? substr($item, $start) : substr($item, $start, $length);
}
/**
 * Returns the first element of the item.
 *
 * @param Twig_Environment $env  A Twig_Environment instance
 * @param mixed            $item A variable
 *
 * @return mixed The first element of the item
 */
function twig_first(Twig_Environment $env, $item)
{
    $elements = twig_slice($env, $item, 0, 1, false);
    return is_string($elements) ? $elements[0] : current($elements);
}
/**
 * Returns the last element of the item.
 *
 * @param Twig_Environment $env  A Twig_Environment instance
 * @param mixed            $item A variable
 *
 * @return mixed The last element of the item
 */
function twig_last(Twig_Environment $env, $item)
{
    $elements = twig_slice($env, $item, -1, 1, false);
    return is_string($elements) ? $elements[0] : current($elements);
}
/**
 * Joins the values to a string.
 *
 * The separator between elements is an empty string per default, you can define it with the optional parameter.
 *
 * <pre>
 *  {{ [1, 2, 3]|join('|') }}
 *  {# returns 1|2|3 #}
 *
 *  {{ [1, 2, 3]|join }}
 *  {# returns 123 #}
 * </pre>
 *
 * @param array  $value An array
 * @param string $glue  The separator
 *
 * @return string The concatenated string
 */
function twig_join_filter($value, $glue = '')
{
    if ($value instanceof Traversable) {
        $value = iterator_to_array($value, false);
    }
    return implode($glue, (array) $value);
}
/**
 * Splits the string into an array.
 *
 * <pre>
 *  {{ "one,two,three"|split(',') }}
 *  {# returns [one, two, three] #}
 *
 *  {{ "one,two,three,four,five"|split(',', 3) }}
 *  {# returns [one, two, "three,four,five"] #}
 *
 *  {{ "123"|split('') }}
 *  {# returns [1, 2, 3] #}
 *
 *  {{ "aabbcc"|split('', 2) }}
 *  {# returns [aa, bb, cc] #}
 * </pre>
 *
 * @param string  $value     A string
 * @param string  $delimiter The delimiter
 * @param integer $limit     The limit
 *
 * @return array The split string as an array
 */
function twig_split_filter($value, $delimiter, $limit = null)
{
    if (empty($delimiter)) {
        return str_split($value, null === $limit ? 1 : $limit);
    }
    return null === $limit ? explode($delimiter, $value) : explode($delimiter, $value, $limit);
}
// The '_default' filter is used internally to avoid using the ternary operator
// which costs a lot for big contexts (before PHP 5.4). So, on average,
// a function call is cheaper.
function _twig_default_filter($value, $default = '')
{
    if (twig_test_empty($value)) {
        return $default;
    }
    return $value;
}
/**
 * Returns the keys for the given array.
 *
 * It is useful when you want to iterate over the keys of an array:
 *
 * <pre>
 *  {% for key in array|keys %}
 *      {# ... #}
 *  {% endfor %}
 * </pre>
 *
 * @param array $array An array
 *
 * @return array The keys
 */
function twig_get_array_keys_filter($array)
{
    if (is_object($array) && $array instanceof Traversable) {
        return array_keys(iterator_to_array($array));
    }
    if (!is_array($array)) {
        return array();
    }
    return array_keys($array);
}
/**
 * Reverses a variable.
 *
 * @param Twig_Environment         $env          A Twig_Environment instance
 * @param array|Traversable|string $item         An array, a Traversable instance, or a string
 * @param Boolean                  $preserveKeys Whether to preserve key or not
 *
 * @return mixed The reversed input
 */
function twig_reverse_filter(Twig_Environment $env, $item, $preserveKeys = false)
{
    if (is_object($item) && $item instanceof Traversable) {
        return array_reverse(iterator_to_array($item), $preserveKeys);
    }
    if (is_array($item)) {
        return array_reverse($item, $preserveKeys);
    }
    if (null !== $charset = $env->getCharset()) {
        $string = (string) $item;
        if ('UTF-8' != $charset) {
            $item = twig_convert_encoding($string, 'UTF-8', $charset);
        }
        preg_match_all('/./us', $item, $matches);
        $string = implode('', array_reverse($matches[0]));
        if ('UTF-8' != $charset) {
            $string = twig_convert_encoding($string, $charset, 'UTF-8');
        }
        return $string;
    }
    return strrev((string) $item);
}
/**
 * Sorts an array.
 *
 * @param array $array An array
 */
function twig_sort_filter($array)
{
    asort($array);
    return $array;
}
/* used internally */
function twig_in_filter($value, $compare)
{
    if (is_array($compare)) {
        return in_array($value, $compare, is_object($value));
    } elseif (is_string($compare)) {
        if (!strlen($value)) {
            return empty($compare);
        }
        return false !== strpos($compare, (string) $value);
    } elseif ($compare instanceof Traversable) {
        return in_array($value, iterator_to_array($compare, false), is_object($value));
    }
    return false;
}
/**
 * Escapes a string.
 *
 * @param Twig_Environment $env        A Twig_Environment instance
 * @param string           $string     The value to be escaped
 * @param string           $strategy   The escaping strategy
 * @param string           $charset    The charset
 * @param Boolean          $autoescape Whether the function is called by the auto-escaping feature (true) or by the developer (false)
 */
function twig_escape_filter(Twig_Environment $env, $string, $strategy = 'html', $charset = null, $autoescape = false)
{
    if ($autoescape && is_object($string) && $string instanceof Twig_Markup) {
        return $string;
    }
    if (!is_string($string) && !(is_object($string) && method_exists($string, '__toString'))) {
        return $string;
    }
    if (null === $charset) {
        $charset = $env->getCharset();
    }
    $string = (string) $string;
    switch ($strategy) {
        case 'js':
            // escape all non-alphanumeric characters
            // into their \xHH or \uHHHH representations
            if ('UTF-8' != $charset) {
                $string = twig_convert_encoding($string, 'UTF-8', $charset);
            }
            if (0 == strlen($string) ? false : (1 == preg_match('/^./su', $string) ? false : true)) {
                throw new Twig_Error_Runtime('The string to escape is not a valid UTF-8 string.');
            }
            $string = preg_replace_callback('#[^a-zA-Z0-9,\._]#Su', '_twig_escape_js_callback', $string);
            if ('UTF-8' != $charset) {
                $string = twig_convert_encoding($string, $charset, 'UTF-8');
            }
            return $string;
        case 'css':
            if ('UTF-8' != $charset) {
                $string = twig_convert_encoding($string, 'UTF-8', $charset);
            }
            if (0 == strlen($string) ? false : (1 == preg_match('/^./su', $string) ? false : true)) {
                throw new Twig_Error_Runtime('The string to escape is not a valid UTF-8 string.');
            }
            $string = preg_replace_callback('#[^a-zA-Z0-9]#Su', '_twig_escape_css_callback', $string);
            if ('UTF-8' != $charset) {
                $string = twig_convert_encoding($string, $charset, 'UTF-8');
            }
            return $string;
        case 'html_attr':
            if ('UTF-8' != $charset) {
                $string = twig_convert_encoding($string, 'UTF-8', $charset);
            }
            if (0 == strlen($string) ? false : (1 == preg_match('/^./su', $string) ? false : true)) {
                throw new Twig_Error_Runtime('The string to escape is not a valid UTF-8 string.');
            }
            $string = preg_replace_callback('#[^a-zA-Z0-9,\.\-_]#Su', '_twig_escape_html_attr_callback', $string);
            if ('UTF-8' != $charset) {
                $string = twig_convert_encoding($string, $charset, 'UTF-8');
            }
            return $string;
        case 'html':
            // see http://php.net/htmlspecialchars
            // Using a static variable to avoid initializing the array
            // each time the function is called. Moving the declaration on the
            // top of the function slow downs other escaping strategies.
            static $htmlspecialcharsCharsets = array(
                'iso-8859-1' => true, 'iso8859-1' => true,
                'iso-8859-15' => true, 'iso8859-15' => true,
                'utf-8' => true,
                'cp866' => true, 'ibm866' => true, '866' => true,
                'cp1251' => true, 'windows-1251' => true, 'win-1251' => true,
                '1251' => true,
                'cp1252' => true, 'windows-1252' => true, '1252' => true,
                'koi8-r' => true, 'koi8-ru' => true, 'koi8r' => true,
                'big5' => true, '950' => true,
                'gb2312' => true, '936' => true,
                'big5-hkscs' => true,
                'shift_jis' => true, 'sjis' => true, '932' => true,
                'euc-jp' => true, 'eucjp' => true,
                'iso8859-5' => true, 'iso-8859-5' => true, 'macroman' => true,
            );
            if (isset($htmlspecialcharsCharsets[strtolower($charset)])) {
                return htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE, $charset);
            }
            $string = twig_convert_encoding($string, 'UTF-8', $charset);
            $string = htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            return twig_convert_encoding($string, $charset, 'UTF-8');
        case 'url':
            if (version_compare(PHP_VERSION, '5.3.0', '<')) {
                return str_replace('%7E', '~', rawurlencode($string));
            }
            return rawurlencode($string);
        default:
            throw new Twig_Error_Runtime(sprintf('Invalid escaping strategy "%s" (valid ones: html, js, url, css, and html_attr).', $strategy));
    }
}
/* used internally */
function twig_escape_filter_is_safe(Twig_Node $filterArgs)
{
    foreach ($filterArgs as $arg) {
        if ($arg instanceof Twig_Node_Expression_Constant) {
            return array($arg->getAttribute('value'));
        }
        return array();
    }
    return array('html');
}
if (function_exists('mb_convert_encoding')) {
    function twig_convert_encoding($string, $to, $from)
    {
        return mb_convert_encoding($string, $to, $from);
    }
} elseif (function_exists('iconv')) {
    function twig_convert_encoding($string, $to, $from)
    {
        return iconv($from, $to, $string);
    }
} else {
    function twig_convert_encoding($string, $to, $from)
    {
        throw new Twig_Error_Runtime('No suitable convert encoding function (use UTF-8 as your encoding or install the iconv or mbstring extension).');
    }
}
function _twig_escape_js_callback($matches)
{
    $char = $matches[0];
    // \xHH
    if (!isset($char[1])) {
        return '\\x'.strtoupper(substr('00'.bin2hex($char), -2));
    }
    // \uHHHH
    $char = twig_convert_encoding($char, 'UTF-16BE', 'UTF-8');
    return '\\u'.strtoupper(substr('0000'.bin2hex($char), -4));
}
function _twig_escape_css_callback($matches)
{
    $char = $matches[0];
    // \xHH
    if (!isset($char[1])) {
        $hex = ltrim(strtoupper(bin2hex($char)), '0');
        if (0 === strlen($hex)) {
            $hex = '0';
        }
        return '\\'.$hex.' ';
    }
    // \uHHHH
    $char = twig_convert_encoding($char, 'UTF-16BE', 'UTF-8');
    return '\\'.ltrim(strtoupper(bin2hex($char)), '0').' ';
}
/**
 * This function is adapted from code coming from Zend Framework.
 *
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
function _twig_escape_html_attr_callback($matches)
{
    /*
     * While HTML supports far more named entities, the lowest common denominator
     * has become HTML5's XML Serialisation which is restricted to the those named
     * entities that XML supports. Using HTML entities would result in this error:
     *     XML Parsing Error: undefined entity
     */
    static $entityMap = array(
        34 => 'quot', /* quotation mark */
        38 => 'amp',  /* ampersand */
        60 => 'lt',   /* less-than sign */
        62 => 'gt',   /* greater-than sign */
    );
    $chr = $matches[0];
    $ord = ord($chr);
    /**
     * The following replaces characters undefined in HTML with the
     * hex entity for the Unicode replacement character.
     */
    if (($ord <= 0x1f && $chr != "\t" && $chr != "\n" && $chr != "\r") || ($ord >= 0x7f && $ord <= 0x9f)) {
        return '&#xFFFD;';
    }
    /**
     * Check if the current character to escape has a name entity we should
     * replace it with while grabbing the hex value of the character.
     */
    if (strlen($chr) == 1) {
        $hex = strtoupper(substr('00'.bin2hex($chr), -2));
    } else {
        $chr = twig_convert_encoding($chr, 'UTF-16BE', 'UTF-8');
        $hex = strtoupper(substr('0000'.bin2hex($chr), -4));
    }
    $int = hexdec($hex);
    if (array_key_exists($int, $entityMap)) {
        return sprintf('&%s;', $entityMap[$int]);
    }
    /**
     * Per OWASP recommendations, we'll use hex entities for any other
     * characters where a named entity does not exist.
     */
    return sprintf('&#x%s;', $hex);
}
// add multibyte extensions if possible
if (function_exists('mb_get_info')) {
    /**
     * Returns the length of a variable.
     *
     * @param Twig_Environment $env   A Twig_Environment instance
     * @param mixed            $thing A variable
     *
     * @return integer The length of the value
     */
    function twig_length_filter(Twig_Environment $env, $thing)
    {
        return is_scalar($thing) ? mb_strlen($thing, $env->getCharset()) : count($thing);
    }
    /**
     * Converts a string to uppercase.
     *
     * @param Twig_Environment $env    A Twig_Environment instance
     * @param string           $string A string
     *
     * @return string The uppercased string
     */
    function twig_upper_filter(Twig_Environment $env, $string)
    {
        if (null !== ($charset = $env->getCharset())) {
            return mb_strtoupper($string, $charset);
        }
        return strtoupper($string);
    }
    /**
     * Converts a string to lowercase.
     *
     * @param Twig_Environment $env    A Twig_Environment instance
     * @param string           $string A string
     *
     * @return string The lowercased string
     */
    function twig_lower_filter(Twig_Environment $env, $string)
    {
        if (null !== ($charset = $env->getCharset())) {
            return mb_strtolower($string, $charset);
        }
        return strtolower($string);
    }
    /**
     * Returns a titlecased string.
     *
     * @param Twig_Environment $env    A Twig_Environment instance
     * @param string           $string A string
     *
     * @return string The titlecased string
     */
    function twig_title_string_filter(Twig_Environment $env, $string)
    {
        if (null !== ($charset = $env->getCharset())) {
            return mb_convert_case($string, MB_CASE_TITLE, $charset);
        }
        return ucwords(strtolower($string));
    }
    /**
     * Returns a capitalized string.
     *
     * @param Twig_Environment $env    A Twig_Environment instance
     * @param string           $string A string
     *
     * @return string The capitalized string
     */
    function twig_capitalize_string_filter(Twig_Environment $env, $string)
    {
        if (null !== ($charset = $env->getCharset())) {
            return mb_strtoupper(mb_substr($string, 0, 1, $charset), $charset).
                         mb_strtolower(mb_substr($string, 1, mb_strlen($string, $charset), $charset), $charset);
        }
        return ucfirst(strtolower($string));
    }
}
// and byte fallback
else {
    /**
     * Returns the length of a variable.
     *
     * @param Twig_Environment $env   A Twig_Environment instance
     * @param mixed            $thing A variable
     *
     * @return integer The length of the value
     */
    function twig_length_filter(Twig_Environment $env, $thing)
    {
        return is_scalar($thing) ? strlen($thing) : count($thing);
    }
    /**
     * Returns a titlecased string.
     *
     * @param Twig_Environment $env    A Twig_Environment instance
     * @param string           $string A string
     *
     * @return string The titlecased string
     */
    function twig_title_string_filter(Twig_Environment $env, $string)
    {
        return ucwords(strtolower($string));
    }
    /**
     * Returns a capitalized string.
     *
     * @param Twig_Environment $env    A Twig_Environment instance
     * @param string           $string A string
     *
     * @return string The capitalized string
     */
    function twig_capitalize_string_filter(Twig_Environment $env, $string)
    {
        return ucfirst(strtolower($string));
    }
}
/* used internally */
function twig_ensure_traversable($seq)
{
    if ($seq instanceof Traversable || is_array($seq)) {
        return $seq;
    }
    return array();
}
/**
 * Checks if a variable is empty.
 *
 * <pre>
 * {# evaluates to true if the foo variable is null, false, or the empty string #}
 * {% if foo is empty %}
 *     {# ... #}
 * {% endif %}
 * </pre>
 *
 * @param mixed $value A variable
 *
 * @return Boolean true if the value is empty, false otherwise
 */
function twig_test_empty($value)
{
    if ($value instanceof Countable) {
        return 0 == count($value);
    }
    return '' === $value || false === $value || null === $value || array() === $value;
}
/**
 * Checks if a variable is traversable.
 *
 * <pre>
 * {# evaluates to true if the foo variable is an array or a traversable object #}
 * {% if foo is traversable %}
 *     {# ... #}
 * {% endif %}
 * </pre>
 *
 * @param mixed $value A variable
 *
 * @return Boolean true if the value is traversable
 */
function twig_test_iterable($value)
{
    return $value instanceof Traversable || is_array($value);
}
/**
 * Renders a template.
 *
 * @param string  template       The template to render
 * @param array   variables      The variables to pass to the template
 * @param Boolean with_context   Whether to pass the current context variables or not
 * @param Boolean ignore_missing Whether to ignore missing templates or not
 * @param Boolean sandboxed      Whether to sandbox the template or not
 *
 * @return string The rendered template
 */
function twig_include(Twig_Environment $env, $context, $template, $variables = array(), $withContext = true, $ignoreMissing = false, $sandboxed = false)
{
    if ($withContext) {
        $variables = array_merge($context, $variables);
    }
    if ($isSandboxed = $sandboxed && $env->hasExtension('sandbox')) {
        $sandbox = $env->getExtension('sandbox');
        if (!$alreadySandboxed = $sandbox->isSandboxed()) {
            $sandbox->enableSandbox();
        }
    }
    try {
        return $env->resolveTemplate($template)->display($variables);
    } catch (Twig_Error_Loader $e) {
        if (!$ignoreMissing) {
            throw $e;
        }
    }
    if ($isSandboxed && !$alreadySandboxed) {
        $sandbox->disableSandbox();
    }
}
/**
 * Provides the ability to get constants from instances as well as class/global constants.
 *
 * @param string      $constant The name of the constant
 * @param null|object $object   The object to get the constant from
 *
 * @return string
 */
function twig_constant($constant, $object = null)
{
    if (null !== $object) {
        $constant = get_class($object).'::'.$constant;
    }
    return constant($constant);
}
/**
 * Batches item.
 *
 * @param array   $items An array of items
 * @param integer $size  The size of the batch
 * @param string  $fill  A string to fill missing items
 *
 * @return array
 */
function twig_array_batch($items, $size, $fill = null)
{
    if ($items instanceof Traversable) {
        $items = iterator_to_array($items, false);
    }
    $size = ceil($size);
    $result = array_chunk($items, $size, true);
    if (null !== $fill) {
        $last = count($result) - 1;
        $result[$last] = array_merge(
            $result[$last],
            array_fill(0, $size - count($result[$last]), $fill)
        );
    }
    return $result;
}

}

namespace
{

/*
 * This file is part of Twig.
 *
 * (c) 2009 Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class Twig_Extension_Escaper extends Twig_Extension
{
    protected $defaultStrategy;
    public function __construct($defaultStrategy = 'html')
    {
        $this->setDefaultStrategy($defaultStrategy);
    }
    /**
     * Returns the token parser instances to add to the existing list.
     *
     * @return array An array of Twig_TokenParserInterface or Twig_TokenParserBrokerInterface instances
     */
    public function getTokenParsers()
    {
        return array(new Twig_TokenParser_AutoEscape());
    }
    /**
     * Returns the node visitor instances to add to the existing list.
     *
     * @return array An array of Twig_NodeVisitorInterface instances
     */
    public function getNodeVisitors()
    {
        return array(new Twig_NodeVisitor_Escaper());
    }
    /**
     * Returns a list of filters to add to the existing list.
     *
     * @return array An array of filters
     */
    public function getFilters()
    {
        return array(
            new Twig_SimpleFilter('raw', 'twig_raw_filter', array('is_safe' => array('all'))),
        );
    }
    /**
     * Sets the default strategy to use when not defined by the user.
     *
     * The strategy can be a valid PHP callback that takes the template
     * "filename" as an argument and returns the strategy to use.
     *
     * @param mixed $defaultStrategy An escaping strategy
     */
    public function setDefaultStrategy($defaultStrategy)
    {
        // for BC
        if (true === $defaultStrategy) {
            $defaultStrategy = 'html';
        }
        $this->defaultStrategy = $defaultStrategy;
    }
    /**
     * Gets the default strategy to use when not defined by the user.
     *
     * @param string $filename The template "filename"
     *
     * @return string The default strategy to use for the template
     */
    public function getDefaultStrategy($filename)
    {
        // disable string callables to avoid calling a function named html or js,
        // or any other upcoming escaping strategy
        if (!is_string($this->defaultStrategy) && is_callable($this->defaultStrategy)) {
            return call_user_func($this->defaultStrategy, $filename);
        }
        return $this->defaultStrategy;
    }
    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'escaper';
    }
}
/**
 * Marks a variable as being safe.
 *
 * @param string $string A PHP variable
 */
function twig_raw_filter($string)
{
    return $string;
}

}

namespace
{

/*
 * This file is part of Twig.
 *
 * (c) 2010 Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class Twig_Extension_Optimizer extends Twig_Extension
{
    protected $optimizers;
    public function __construct($optimizers = -1)
    {
        $this->optimizers = $optimizers;
    }
    /**
     * {@inheritdoc}
     */
    public function getNodeVisitors()
    {
        return array(new Twig_NodeVisitor_Optimizer($this->optimizers));
    }
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'optimizer';
    }
}

}

namespace
{

/*
 * This file is part of Twig.
 *
 * (c) 2009 Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
/**
 * Interface all loaders must implement.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
interface Twig_LoaderInterface
{
    /**
     * Gets the source code of a template, given its name.
     *
     * @param string $name The name of the template to load
     *
     * @return string The template source code
     *
     * @throws Twig_Error_Loader When $name is not found
     */
    public function getSource($name);
    /**
     * Gets the cache key to use for the cache for a given template name.
     *
     * @param string $name The name of the template to load
     *
     * @return string The cache key
     *
     * @throws Twig_Error_Loader When $name is not found
     */
    public function getCacheKey($name);
    /**
     * Returns true if the template is still fresh.
     *
     * @param string    $name The template name
     * @param timestamp $time The last modification time of the cached template
     *
     * @return Boolean true if the template is fresh, false otherwise
     *
     * @throws Twig_Error_Loader When $name is not found
     */
    public function isFresh($name, $time);
}

}

namespace
{

/*
 * This file is part of Twig.
 *
 * (c) 2010 Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
/**
 * Marks a content as safe.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class Twig_Markup implements Countable
{
    protected $content;
    protected $charset;
    public function __construct($content, $charset)
    {
        $this->content = (string) $content;
        $this->charset = $charset;
    }
    public function __toString()
    {
        return $this->content;
    }
    public function count()
    {
        return function_exists('mb_get_info') ? mb_strlen($this->content, $this->charset) : strlen($this->content);
    }
}

}

namespace
{

/*
 * This file is part of Twig.
 *
 * (c) 2009 Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
/**
 * Interface implemented by all compiled templates.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @deprecated since 1.12 (to be removed in 2.0)
 */
interface Twig_TemplateInterface
{
    const ANY_CALL    = 'any';
    const ARRAY_CALL  = 'array';
    const METHOD_CALL = 'method';
    /**
     * Renders the template with the given context and returns it as string.
     *
     * @param array $context An array of parameters to pass to the template
     *
     * @return string The rendered template
     */
    public function render(array $context);
    /**
     * Displays the template with the given context.
     *
     * @param array $context An array of parameters to pass to the template
     * @param array $blocks  An array of blocks to pass to the template
     */
    public function display(array $context, array $blocks = array());
    /**
     * Returns the bound environment for this template.
     *
     * @return Twig_Environment The current environment
     */
    public function getEnvironment();
}

}

namespace
{

/*
 * This file is part of Twig.
 *
 * (c) 2009 Fabien Potencier
 * (c) 2009 Armin Ronacher
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
/**
 * Default base class for compiled templates.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
abstract class Twig_Template implements Twig_TemplateInterface
{
    protected static $cache = array();
    protected $parent;
    protected $parents;
    protected $env;
    protected $blocks;
    protected $traits;
    /**
     * Constructor.
     *
     * @param Twig_Environment $env A Twig_Environment instance
     */
    public function __construct(Twig_Environment $env)
    {
        $this->env = $env;
        $this->blocks = array();
        $this->traits = array();
    }
    /**
     * Returns the template name.
     *
     * @return string The template name
     */
    abstract public function getTemplateName();
    /**
     * {@inheritdoc}
     */
    public function getEnvironment()
    {
        return $this->env;
    }
    /**
     * Returns the parent template.
     *
     * This method is for internal use only and should never be called
     * directly.
     *
     * @return Twig_TemplateInterface|false The parent template or false if there is no parent
     */
    public function getParent(array $context)
    {
        if (null !== $this->parent) {
            return $this->parent;
        }
        $parent = $this->doGetParent($context);
        if (false === $parent) {
            return false;
        } elseif ($parent instanceof Twig_Template) {
            $name = $parent->getTemplateName();
            $this->parents[$name] = $parent;
            $parent = $name;
        } elseif (!isset($this->parents[$parent])) {
            $this->parents[$parent] = $this->env->loadTemplate($parent);
        }
        return $this->parents[$parent];
    }
    protected function doGetParent(array $context)
    {
        return false;
    }
    public function isTraitable()
    {
        return true;
    }
    /**
     * Displays a parent block.
     *
     * This method is for internal use only and should never be called
     * directly.
     *
     * @param string $name    The block name to display from the parent
     * @param array  $context The context
     * @param array  $blocks  The current set of blocks
     */
    public function displayParentBlock($name, array $context, array $blocks = array())
    {
        $name = (string) $name;
        if (isset($this->traits[$name])) {
            $this->traits[$name][0]->displayBlock($name, $context, $blocks);
        } elseif (false !== $parent = $this->getParent($context)) {
            $parent->displayBlock($name, $context, $blocks);
        } else {
            throw new Twig_Error_Runtime(sprintf('The template has no parent and no traits defining the "%s" block', $name), -1, $this->getTemplateName());
        }
    }
    /**
     * Displays a block.
     *
     * This method is for internal use only and should never be called
     * directly.
     *
     * @param string $name    The block name to display
     * @param array  $context The context
     * @param array  $blocks  The current set of blocks
     */
    public function displayBlock($name, array $context, array $blocks = array())
    {
        $name = (string) $name;
        if (isset($blocks[$name])) {
            $b = $blocks;
            unset($b[$name]);
            call_user_func($blocks[$name], $context, $b);
        } elseif (isset($this->blocks[$name])) {
            call_user_func($this->blocks[$name], $context, $blocks);
        } elseif (false !== $parent = $this->getParent($context)) {
            $parent->displayBlock($name, $context, array_merge($this->blocks, $blocks));
        }
    }
    /**
     * Renders a parent block.
     *
     * This method is for internal use only and should never be called
     * directly.
     *
     * @param string $name    The block name to render from the parent
     * @param array  $context The context
     * @param array  $blocks  The current set of blocks
     *
     * @return string The rendered block
     */
    public function renderParentBlock($name, array $context, array $blocks = array())
    {
        ob_start();
        $this->displayParentBlock($name, $context, $blocks);
        return ob_get_clean();
    }
    /**
     * Renders a block.
     *
     * This method is for internal use only and should never be called
     * directly.
     *
     * @param string $name    The block name to render
     * @param array  $context The context
     * @param array  $blocks  The current set of blocks
     *
     * @return string The rendered block
     */
    public function renderBlock($name, array $context, array $blocks = array())
    {
        ob_start();
        $this->displayBlock($name, $context, $blocks);
        return ob_get_clean();
    }
    /**
     * Returns whether a block exists or not.
     *
     * This method is for internal use only and should never be called
     * directly.
     *
     * This method does only return blocks defined in the current template
     * or defined in "used" traits.
     *
     * It does not return blocks from parent templates as the parent
     * template name can be dynamic, which is only known based on the
     * current context.
     *
     * @param string $name The block name
     *
     * @return Boolean true if the block exists, false otherwise
     */
    public function hasBlock($name)
    {
        return isset($this->blocks[(string) $name]);
    }
    /**
     * Returns all block names.
     *
     * This method is for internal use only and should never be called
     * directly.
     *
     * @return array An array of block names
     *
     * @see hasBlock
     */
    public function getBlockNames()
    {
        return array_keys($this->blocks);
    }
    /**
     * Returns all blocks.
     *
     * This method is for internal use only and should never be called
     * directly.
     *
     * @return array An array of blocks
     *
     * @see hasBlock
     */
    public function getBlocks()
    {
        return $this->blocks;
    }
    /**
     * {@inheritdoc}
     */
    public function display(array $context, array $blocks = array())
    {
        $this->displayWithErrorHandling($this->env->mergeGlobals($context), $blocks);
    }
    /**
     * {@inheritdoc}
     */
    public function render(array $context)
    {
        $level = ob_get_level();
        ob_start();
        try {
            $this->display($context);
        } catch (Exception $e) {
            while (ob_get_level() > $level) {
                ob_end_clean();
            }
            throw $e;
        }
        return ob_get_clean();
    }
    protected function displayWithErrorHandling(array $context, array $blocks = array())
    {
        try {
            $this->doDisplay($context, $blocks);
        } catch (Twig_Error $e) {
            if (!$e->getTemplateFile()) {
                $e->setTemplateFile($this->getTemplateName());
            }
            // this is mostly useful for Twig_Error_Loader exceptions
            // see Twig_Error_Loader
            if (false === $e->getTemplateLine()) {
                $e->setTemplateLine(-1);
                $e->guess();
            }
            throw $e;
        } catch (Exception $e) {
            throw new Twig_Error_Runtime(sprintf('An exception has been thrown during the rendering of a template ("%s").', $e->getMessage()), -1, null, $e);
        }
    }
    /**
     * Auto-generated method to display the template with the given context.
     *
     * @param array $context An array of parameters to pass to the template
     * @param array $blocks  An array of blocks to pass to the template
     */
    abstract protected function doDisplay(array $context, array $blocks = array());
    /**
     * Returns a variable from the context.
     *
     * This method is for internal use only and should never be called
     * directly.
     *
     * This method should not be overridden in a sub-class as this is an
     * implementation detail that has been introduced to optimize variable
     * access for versions of PHP before 5.4. This is not a way to override
     * the way to get a variable value.
     *
     * @param array   $context           The context
     * @param string  $item              The variable to return from the context
     * @param Boolean $ignoreStrictCheck Whether to ignore the strict variable check or not
     *
     * @return The content of the context variable
     *
     * @throws Twig_Error_Runtime if the variable does not exist and Twig is running in strict mode
     */
    final protected function getContext($context, $item, $ignoreStrictCheck = false)
    {
        if (!array_key_exists($item, $context)) {
            if ($ignoreStrictCheck || !$this->env->isStrictVariables()) {
                return null;
            }
            throw new Twig_Error_Runtime(sprintf('Variable "%s" does not exist', $item), -1, $this->getTemplateName());
        }
        return $context[$item];
    }
    /**
     * Returns the attribute value for a given array/object.
     *
     * @param mixed   $object            The object or array from where to get the item
     * @param mixed   $item              The item to get from the array or object
     * @param array   $arguments         An array of arguments to pass if the item is an object method
     * @param string  $type              The type of attribute (@see Twig_TemplateInterface)
     * @param Boolean $isDefinedTest     Whether this is only a defined check
     * @param Boolean $ignoreStrictCheck Whether to ignore the strict attribute check or not
     *
     * @return mixed The attribute value, or a Boolean when $isDefinedTest is true, or null when the attribute is not set and $ignoreStrictCheck is true
     *
     * @throws Twig_Error_Runtime if the attribute does not exist and Twig is running in strict mode and $isDefinedTest is false
     */
    protected function getAttribute($object, $item, array $arguments = array(), $type = Twig_TemplateInterface::ANY_CALL, $isDefinedTest = false, $ignoreStrictCheck = false)
    {
        $item = ctype_digit((string) $item) ? (int) $item : (string) $item;
        // array
        if (Twig_TemplateInterface::METHOD_CALL !== $type) {
            if ((is_array($object) && array_key_exists($item, $object))
                || ($object instanceof ArrayAccess && isset($object[$item]))
            ) {
                if ($isDefinedTest) {
                    return true;
                }
                return $object[$item];
            }
            if (Twig_TemplateInterface::ARRAY_CALL === $type) {
                if ($isDefinedTest) {
                    return false;
                }
                if ($ignoreStrictCheck || !$this->env->isStrictVariables()) {
                    return null;
                }
                if (is_object($object)) {
                    throw new Twig_Error_Runtime(sprintf('Key "%s" in object (with ArrayAccess) of type "%s" does not exist', $item, get_class($object)), -1, $this->getTemplateName());
                } elseif (is_array($object)) {
                    throw new Twig_Error_Runtime(sprintf('Key "%s" for array with keys "%s" does not exist', $item, implode(', ', array_keys($object))), -1, $this->getTemplateName());
                } else {
                    throw new Twig_Error_Runtime(sprintf('Impossible to access a key ("%s") on a "%s" variable', $item, gettype($object)), -1, $this->getTemplateName());
                }
            }
        }
        if (!is_object($object)) {
            if ($isDefinedTest) {
                return false;
            }
            if ($ignoreStrictCheck || !$this->env->isStrictVariables()) {
                return null;
            }
            throw new Twig_Error_Runtime(sprintf('Item "%s" for "%s" does not exist', $item, is_array($object) ? 'Array' : $object), -1, $this->getTemplateName());
        }
        $class = get_class($object);
        // object property
        if (Twig_TemplateInterface::METHOD_CALL !== $type) {
            if (isset($object->$item) || array_key_exists($item, $object)) {
                if ($isDefinedTest) {
                    return true;
                }
                if ($this->env->hasExtension('sandbox')) {
                    $this->env->getExtension('sandbox')->checkPropertyAllowed($object, $item);
                }
                return $object->$item;
            }
        }
        // object method
        if (!isset(self::$cache[$class]['methods'])) {
            self::$cache[$class]['methods'] = array_change_key_case(array_flip(get_class_methods($object)));
        }
        $lcItem = strtolower($item);
        if (isset(self::$cache[$class]['methods'][$lcItem])) {
            $method = $item;
        } elseif (isset(self::$cache[$class]['methods']['get'.$lcItem])) {
            $method = 'get'.$item;
        } elseif (isset(self::$cache[$class]['methods']['is'.$lcItem])) {
            $method = 'is'.$item;
        } elseif (isset(self::$cache[$class]['methods']['__call'])) {
            $method = $item;
        } else {
            if ($isDefinedTest) {
                return false;
            }
            if ($ignoreStrictCheck || !$this->env->isStrictVariables()) {
                return null;
            }
            throw new Twig_Error_Runtime(sprintf('Method "%s" for object "%s" does not exist', $item, get_class($object)), -1, $this->getTemplateName());
        }
        if ($isDefinedTest) {
            return true;
        }
        if ($this->env->hasExtension('sandbox')) {
            $this->env->getExtension('sandbox')->checkMethodAllowed($object, $method);
        }
        $ret = call_user_func_array(array($object, $method), $arguments);
        // useful when calling a template method from a template
        // this is not supported but unfortunately heavily used in the Symfony profiler
        if ($object instanceof Twig_TemplateInterface) {
            return $ret === '' ? '' : new Twig_Markup($ret, $this->env->getCharset());
        }
        return $ret;
    }
    /**
     * This method is only useful when testing Twig. Do not use it.
     */
    public static function clearCache()
    {
        self::$cache = array();
    }
}

}
