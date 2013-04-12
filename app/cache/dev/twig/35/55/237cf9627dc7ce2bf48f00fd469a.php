<?php

/* TperroinTremplinFoyerBundle:Association:index.html.twig */
class __TwigTemplate_3555237cf9627dc7ce2bf48f00fd469a extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("::base.html.twig");

        $this->blocks = array(
            'corps' => array($this, 'block_corps'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "::base.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 2
    public function block_corps($context, array $blocks = array())
    {
        // line 3
        echo "    <div class=\"row\">
        <div class=\"large-12\">
            <h1 class=\"bleu\">/// L'association</h1>
        </div>
    </div>

    ";
        // line 9
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getContext($context, "entities"));
        foreach ($context['_seq'] as $context["_key"] => $context["entity"]) {
            // line 10
            echo "
    
          
        <div class=\"row\">
            <div class=\"large-12\">
                <div class=\"row blocBlanc\">
                    <h5><a class=\"bleu\" href=\"";
            // line 16
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("association_show", array("id" => $this->getAttribute($this->getContext($context, "entity"), "id"))), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "entity"), "titre"), "html", null, true);
            echo "</a></h5>
                    <div class=\"large-2 columns columns\">
                        ";
            // line 18
            echo $this->env->getExtension('sonata_media')->media($this->getAttribute($this->getContext($context, "entity"), "image"), "small", array());
            // line 19
            echo "                    </div>
                    <div class=\"large-10 columns\">
                        
                        
                    <div class=\"row\"></div>
                        <p>";
            // line 24
            echo $this->getAttribute($this->getContext($context, "entity"), "teaser");
            echo "</p>
                    </div>
                </div>
            </div>
        </div>
            
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['entity'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 31
        echo "
";
    }

    public function getTemplateName()
    {
        return "TperroinTremplinFoyerBundle:Association:index.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  80 => 31,  67 => 24,  60 => 19,  58 => 18,  51 => 16,  43 => 10,  39 => 9,  31 => 3,  28 => 2,);
    }
}
