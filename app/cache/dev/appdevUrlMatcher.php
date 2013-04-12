<?php

use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RequestContext;

/**
 * appdevUrlMatcher
 *
 * This class has been auto-generated
 * by the Symfony Routing Component.
 */
class appdevUrlMatcher extends Symfony\Bundle\FrameworkBundle\Routing\RedirectableUrlMatcher
{
    /**
     * Constructor.
     */
    public function __construct(RequestContext $context)
    {
        $this->context = $context;
    }

    public function match($pathinfo)
    {
        $allow = array();
        $pathinfo = rawurldecode($pathinfo);

        // _wdt
        if (0 === strpos($pathinfo, '/_wdt') && preg_match('#^/_wdt/(?<token>[^/]+)$#s', $pathinfo, $matches)) {
            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Symfony\\Bundle\\WebProfilerBundle\\Controller\\ProfilerController::toolbarAction',)), array('_route' => '_wdt'));
        }

        if (0 === strpos($pathinfo, '/_profiler')) {
            // _profiler_search
            if ($pathinfo === '/_profiler/search') {
                return array (  '_controller' => 'Symfony\\Bundle\\WebProfilerBundle\\Controller\\ProfilerController::searchAction',  '_route' => '_profiler_search',);
            }

            // _profiler_purge
            if ($pathinfo === '/_profiler/purge') {
                return array (  '_controller' => 'Symfony\\Bundle\\WebProfilerBundle\\Controller\\ProfilerController::purgeAction',  '_route' => '_profiler_purge',);
            }

            // _profiler_info
            if (0 === strpos($pathinfo, '/_profiler/info') && preg_match('#^/_profiler/info/(?<about>[^/]+)$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Symfony\\Bundle\\WebProfilerBundle\\Controller\\ProfilerController::infoAction',)), array('_route' => '_profiler_info'));
            }

            // _profiler_import
            if ($pathinfo === '/_profiler/import') {
                return array (  '_controller' => 'Symfony\\Bundle\\WebProfilerBundle\\Controller\\ProfilerController::importAction',  '_route' => '_profiler_import',);
            }

            // _profiler_export
            if (0 === strpos($pathinfo, '/_profiler/export') && preg_match('#^/_profiler/export/(?<token>[^/\\.]+)\\.txt$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Symfony\\Bundle\\WebProfilerBundle\\Controller\\ProfilerController::exportAction',)), array('_route' => '_profiler_export'));
            }

            // _profiler_phpinfo
            if ($pathinfo === '/_profiler/phpinfo') {
                return array (  '_controller' => 'Symfony\\Bundle\\WebProfilerBundle\\Controller\\ProfilerController::phpinfoAction',  '_route' => '_profiler_phpinfo',);
            }

            // _profiler_search_results
            if (preg_match('#^/_profiler/(?<token>[^/]+)/search/results$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Symfony\\Bundle\\WebProfilerBundle\\Controller\\ProfilerController::searchResultsAction',)), array('_route' => '_profiler_search_results'));
            }

            // _profiler
            if (preg_match('#^/_profiler/(?<token>[^/]+)$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Symfony\\Bundle\\WebProfilerBundle\\Controller\\ProfilerController::panelAction',)), array('_route' => '_profiler'));
            }

            // _profiler_redirect
            if (rtrim($pathinfo, '/') === '/_profiler') {
                if (substr($pathinfo, -1) !== '/') {
                    return $this->redirect($pathinfo.'/', '_profiler_redirect');
                }

                return array (  '_controller' => 'Symfony\\Bundle\\FrameworkBundle\\Controller\\RedirectController::redirectAction',  'route' => '_profiler_search_results',  'token' => 'empty',  'ip' => '',  'url' => '',  'method' => '',  'limit' => '10',  '_route' => '_profiler_redirect',);
            }

        }

        if (0 === strpos($pathinfo, '/_configurator')) {
            // _configurator_home
            if (rtrim($pathinfo, '/') === '/_configurator') {
                if (substr($pathinfo, -1) !== '/') {
                    return $this->redirect($pathinfo.'/', '_configurator_home');
                }

                return array (  '_controller' => 'Sensio\\Bundle\\DistributionBundle\\Controller\\ConfiguratorController::checkAction',  '_route' => '_configurator_home',);
            }

            // _configurator_step
            if (0 === strpos($pathinfo, '/_configurator/step') && preg_match('#^/_configurator/step/(?<index>[^/]+)$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sensio\\Bundle\\DistributionBundle\\Controller\\ConfiguratorController::stepAction',)), array('_route' => '_configurator_step'));
            }

            // _configurator_final
            if ($pathinfo === '/_configurator/final') {
                return array (  '_controller' => 'Sensio\\Bundle\\DistributionBundle\\Controller\\ConfiguratorController::finalAction',  '_route' => '_configurator_final',);
            }

        }

        // tperroin_tremplin_foyer_homepage
        if (0 === strpos($pathinfo, '/hello') && preg_match('#^/hello/(?<name>[^/]+)$#s', $pathinfo, $matches)) {
            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Tperroin\\TremplinFoyerBundle\\Controller\\DefaultController::indexAction',)), array('_route' => 'tperroin_tremplin_foyer_homepage'));
        }

        if (0 === strpos($pathinfo, '/admin')) {
            // sonata_admin_dashboard
            if ($pathinfo === '/admin/dashboard') {
                return array (  '_controller' => 'Sonata\\AdminBundle\\Controller\\CoreController::dashboardAction',  '_route' => 'sonata_admin_dashboard',);
            }

            // sonata_admin_retrieve_form_element
            if ($pathinfo === '/admin/core/get-form-field-element') {
                return array (  '_controller' => 'sonata.admin.controller.admin:retrieveFormFieldElementAction',  '_route' => 'sonata_admin_retrieve_form_element',);
            }

            // sonata_admin_append_form_element
            if ($pathinfo === '/admin/core/append-form-field-element') {
                return array (  '_controller' => 'sonata.admin.controller.admin:appendFormFieldElementAction',  '_route' => 'sonata_admin_append_form_element',);
            }

            // sonata_admin_short_object_information
            if ($pathinfo === '/admin/core/get-short-object-description') {
                return array (  '_controller' => 'sonata.admin.controller.admin:getShortObjectDescriptionAction',  '_route' => 'sonata_admin_short_object_information',);
            }

            // sonata_admin_set_object_field_value
            if ($pathinfo === '/admin/core/set-object-field-value') {
                return array (  '_controller' => 'sonata.admin.controller.admin:setObjectFieldValueAction',  '_route' => 'sonata_admin_set_object_field_value',);
            }

        }

        if (0 === strpos($pathinfo, '/admin')) {
            // admin_sonata_user_user_list
            if ($pathinfo === '/admin/sonata/user/user/list') {
                return array (  '_controller' => 'Sonata\\AdminBundle\\Controller\\CRUDController::listAction',  '_sonata_admin' => 'sonata.user.admin.user',  '_sonata_name' => 'admin_sonata_user_user_list',  '_route' => 'admin_sonata_user_user_list',);
            }

            // admin_sonata_user_user_create
            if ($pathinfo === '/admin/sonata/user/user/create') {
                return array (  '_controller' => 'Sonata\\AdminBundle\\Controller\\CRUDController::createAction',  '_sonata_admin' => 'sonata.user.admin.user',  '_sonata_name' => 'admin_sonata_user_user_create',  '_route' => 'admin_sonata_user_user_create',);
            }

            // admin_sonata_user_user_batch
            if ($pathinfo === '/admin/sonata/user/user/batch') {
                return array (  '_controller' => 'Sonata\\AdminBundle\\Controller\\CRUDController::batchAction',  '_sonata_admin' => 'sonata.user.admin.user',  '_sonata_name' => 'admin_sonata_user_user_batch',  '_route' => 'admin_sonata_user_user_batch',);
            }

            // admin_sonata_user_user_edit
            if (0 === strpos($pathinfo, '/admin/sonata/user/user') && preg_match('#^/admin/sonata/user/user/(?<id>[^/]+)/edit$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sonata\\AdminBundle\\Controller\\CRUDController::editAction',  '_sonata_admin' => 'sonata.user.admin.user',  '_sonata_name' => 'admin_sonata_user_user_edit',)), array('_route' => 'admin_sonata_user_user_edit'));
            }

            // admin_sonata_user_user_delete
            if (0 === strpos($pathinfo, '/admin/sonata/user/user') && preg_match('#^/admin/sonata/user/user/(?<id>[^/]+)/delete$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sonata\\AdminBundle\\Controller\\CRUDController::deleteAction',  '_sonata_admin' => 'sonata.user.admin.user',  '_sonata_name' => 'admin_sonata_user_user_delete',)), array('_route' => 'admin_sonata_user_user_delete'));
            }

            // admin_sonata_user_user_show
            if (0 === strpos($pathinfo, '/admin/sonata/user/user') && preg_match('#^/admin/sonata/user/user/(?<id>[^/]+)/show$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sonata\\AdminBundle\\Controller\\CRUDController::showAction',  '_sonata_admin' => 'sonata.user.admin.user',  '_sonata_name' => 'admin_sonata_user_user_show',)), array('_route' => 'admin_sonata_user_user_show'));
            }

            // admin_sonata_user_user_export
            if ($pathinfo === '/admin/sonata/user/user/export') {
                return array (  '_controller' => 'Sonata\\AdminBundle\\Controller\\CRUDController::exportAction',  '_sonata_admin' => 'sonata.user.admin.user',  '_sonata_name' => 'admin_sonata_user_user_export',  '_route' => 'admin_sonata_user_user_export',);
            }

            // admin_sonata_user_group_list
            if ($pathinfo === '/admin/sonata/user/group/list') {
                return array (  '_controller' => 'Sonata\\AdminBundle\\Controller\\CRUDController::listAction',  '_sonata_admin' => 'sonata.user.admin.group',  '_sonata_name' => 'admin_sonata_user_group_list',  '_route' => 'admin_sonata_user_group_list',);
            }

            // admin_sonata_user_group_create
            if ($pathinfo === '/admin/sonata/user/group/create') {
                return array (  '_controller' => 'Sonata\\AdminBundle\\Controller\\CRUDController::createAction',  '_sonata_admin' => 'sonata.user.admin.group',  '_sonata_name' => 'admin_sonata_user_group_create',  '_route' => 'admin_sonata_user_group_create',);
            }

            // admin_sonata_user_group_batch
            if ($pathinfo === '/admin/sonata/user/group/batch') {
                return array (  '_controller' => 'Sonata\\AdminBundle\\Controller\\CRUDController::batchAction',  '_sonata_admin' => 'sonata.user.admin.group',  '_sonata_name' => 'admin_sonata_user_group_batch',  '_route' => 'admin_sonata_user_group_batch',);
            }

            // admin_sonata_user_group_edit
            if (0 === strpos($pathinfo, '/admin/sonata/user/group') && preg_match('#^/admin/sonata/user/group/(?<id>[^/]+)/edit$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sonata\\AdminBundle\\Controller\\CRUDController::editAction',  '_sonata_admin' => 'sonata.user.admin.group',  '_sonata_name' => 'admin_sonata_user_group_edit',)), array('_route' => 'admin_sonata_user_group_edit'));
            }

            // admin_sonata_user_group_delete
            if (0 === strpos($pathinfo, '/admin/sonata/user/group') && preg_match('#^/admin/sonata/user/group/(?<id>[^/]+)/delete$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sonata\\AdminBundle\\Controller\\CRUDController::deleteAction',  '_sonata_admin' => 'sonata.user.admin.group',  '_sonata_name' => 'admin_sonata_user_group_delete',)), array('_route' => 'admin_sonata_user_group_delete'));
            }

            // admin_sonata_user_group_show
            if (0 === strpos($pathinfo, '/admin/sonata/user/group') && preg_match('#^/admin/sonata/user/group/(?<id>[^/]+)/show$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sonata\\AdminBundle\\Controller\\CRUDController::showAction',  '_sonata_admin' => 'sonata.user.admin.group',  '_sonata_name' => 'admin_sonata_user_group_show',)), array('_route' => 'admin_sonata_user_group_show'));
            }

            // admin_sonata_user_group_export
            if ($pathinfo === '/admin/sonata/user/group/export') {
                return array (  '_controller' => 'Sonata\\AdminBundle\\Controller\\CRUDController::exportAction',  '_sonata_admin' => 'sonata.user.admin.group',  '_sonata_name' => 'admin_sonata_user_group_export',  '_route' => 'admin_sonata_user_group_export',);
            }

            // admin_sonata_news_post_list
            if ($pathinfo === '/admin/sonata/news/post/list') {
                return array (  '_controller' => 'Sonata\\AdminBundle\\Controller\\CRUDController::listAction',  '_sonata_admin' => 'sonata.news.admin.post',  '_sonata_name' => 'admin_sonata_news_post_list',  '_route' => 'admin_sonata_news_post_list',);
            }

            // admin_sonata_news_post_create
            if ($pathinfo === '/admin/sonata/news/post/create') {
                return array (  '_controller' => 'Sonata\\AdminBundle\\Controller\\CRUDController::createAction',  '_sonata_admin' => 'sonata.news.admin.post',  '_sonata_name' => 'admin_sonata_news_post_create',  '_route' => 'admin_sonata_news_post_create',);
            }

            // admin_sonata_news_post_batch
            if ($pathinfo === '/admin/sonata/news/post/batch') {
                return array (  '_controller' => 'Sonata\\AdminBundle\\Controller\\CRUDController::batchAction',  '_sonata_admin' => 'sonata.news.admin.post',  '_sonata_name' => 'admin_sonata_news_post_batch',  '_route' => 'admin_sonata_news_post_batch',);
            }

            // admin_sonata_news_post_edit
            if (0 === strpos($pathinfo, '/admin/sonata/news/post') && preg_match('#^/admin/sonata/news/post/(?<id>[^/]+)/edit$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sonata\\AdminBundle\\Controller\\CRUDController::editAction',  '_sonata_admin' => 'sonata.news.admin.post',  '_sonata_name' => 'admin_sonata_news_post_edit',)), array('_route' => 'admin_sonata_news_post_edit'));
            }

            // admin_sonata_news_post_delete
            if (0 === strpos($pathinfo, '/admin/sonata/news/post') && preg_match('#^/admin/sonata/news/post/(?<id>[^/]+)/delete$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sonata\\AdminBundle\\Controller\\CRUDController::deleteAction',  '_sonata_admin' => 'sonata.news.admin.post',  '_sonata_name' => 'admin_sonata_news_post_delete',)), array('_route' => 'admin_sonata_news_post_delete'));
            }

            // admin_sonata_news_post_show
            if (0 === strpos($pathinfo, '/admin/sonata/news/post') && preg_match('#^/admin/sonata/news/post/(?<id>[^/]+)/show$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sonata\\AdminBundle\\Controller\\CRUDController::showAction',  '_sonata_admin' => 'sonata.news.admin.post',  '_sonata_name' => 'admin_sonata_news_post_show',)), array('_route' => 'admin_sonata_news_post_show'));
            }

            // admin_sonata_news_post_export
            if ($pathinfo === '/admin/sonata/news/post/export') {
                return array (  '_controller' => 'Sonata\\AdminBundle\\Controller\\CRUDController::exportAction',  '_sonata_admin' => 'sonata.news.admin.post',  '_sonata_name' => 'admin_sonata_news_post_export',  '_route' => 'admin_sonata_news_post_export',);
            }

            // admin_sonata_news_post_comment_list
            if (0 === strpos($pathinfo, '/admin/sonata/news/post') && preg_match('#^/admin/sonata/news/post/(?<id>[^/]+)/comment/list$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sonata\\NewsBundle\\Controller\\CommentAdminController::listAction',  '_sonata_admin' => 'sonata.news.admin.post|sonata.news.admin.comment',  '_sonata_name' => 'admin_sonata_news_post_comment_list',)), array('_route' => 'admin_sonata_news_post_comment_list'));
            }

            // admin_sonata_news_post_comment_create
            if (0 === strpos($pathinfo, '/admin/sonata/news/post') && preg_match('#^/admin/sonata/news/post/(?<id>[^/]+)/comment/create$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sonata\\NewsBundle\\Controller\\CommentAdminController::createAction',  '_sonata_admin' => 'sonata.news.admin.post|sonata.news.admin.comment',  '_sonata_name' => 'admin_sonata_news_post_comment_create',)), array('_route' => 'admin_sonata_news_post_comment_create'));
            }

            // admin_sonata_news_post_comment_batch
            if (0 === strpos($pathinfo, '/admin/sonata/news/post') && preg_match('#^/admin/sonata/news/post/(?<id>[^/]+)/comment/batch$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sonata\\NewsBundle\\Controller\\CommentAdminController::batchAction',  '_sonata_admin' => 'sonata.news.admin.post|sonata.news.admin.comment',  '_sonata_name' => 'admin_sonata_news_post_comment_batch',)), array('_route' => 'admin_sonata_news_post_comment_batch'));
            }

            // admin_sonata_news_post_comment_edit
            if (0 === strpos($pathinfo, '/admin/sonata/news/post') && preg_match('#^/admin/sonata/news/post/(?<id>[^/]+)/comment/(?<childId>[^/]+)/edit$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sonata\\NewsBundle\\Controller\\CommentAdminController::editAction',  '_sonata_admin' => 'sonata.news.admin.post|sonata.news.admin.comment',  '_sonata_name' => 'admin_sonata_news_post_comment_edit',)), array('_route' => 'admin_sonata_news_post_comment_edit'));
            }

            // admin_sonata_news_post_comment_delete
            if (0 === strpos($pathinfo, '/admin/sonata/news/post') && preg_match('#^/admin/sonata/news/post/(?<id>[^/]+)/comment/(?<childId>[^/]+)/delete$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sonata\\NewsBundle\\Controller\\CommentAdminController::deleteAction',  '_sonata_admin' => 'sonata.news.admin.post|sonata.news.admin.comment',  '_sonata_name' => 'admin_sonata_news_post_comment_delete',)), array('_route' => 'admin_sonata_news_post_comment_delete'));
            }

            // admin_sonata_news_post_comment_show
            if (0 === strpos($pathinfo, '/admin/sonata/news/post') && preg_match('#^/admin/sonata/news/post/(?<id>[^/]+)/comment/(?<childId>[^/]+)/show$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sonata\\NewsBundle\\Controller\\CommentAdminController::showAction',  '_sonata_admin' => 'sonata.news.admin.post|sonata.news.admin.comment',  '_sonata_name' => 'admin_sonata_news_post_comment_show',)), array('_route' => 'admin_sonata_news_post_comment_show'));
            }

            // admin_sonata_news_post_comment_export
            if (0 === strpos($pathinfo, '/admin/sonata/news/post') && preg_match('#^/admin/sonata/news/post/(?<id>[^/]+)/comment/export$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sonata\\NewsBundle\\Controller\\CommentAdminController::exportAction',  '_sonata_admin' => 'sonata.news.admin.post|sonata.news.admin.comment',  '_sonata_name' => 'admin_sonata_news_post_comment_export',)), array('_route' => 'admin_sonata_news_post_comment_export'));
            }

            // admin_sonata_news_comment_list
            if ($pathinfo === '/admin/sonata/news/comment/list') {
                return array (  '_controller' => 'Sonata\\NewsBundle\\Controller\\CommentAdminController::listAction',  '_sonata_admin' => 'sonata.news.admin.comment',  '_sonata_name' => 'admin_sonata_news_comment_list',  '_route' => 'admin_sonata_news_comment_list',);
            }

            // admin_sonata_news_comment_create
            if ($pathinfo === '/admin/sonata/news/comment/create') {
                return array (  '_controller' => 'Sonata\\NewsBundle\\Controller\\CommentAdminController::createAction',  '_sonata_admin' => 'sonata.news.admin.comment',  '_sonata_name' => 'admin_sonata_news_comment_create',  '_route' => 'admin_sonata_news_comment_create',);
            }

            // admin_sonata_news_comment_batch
            if ($pathinfo === '/admin/sonata/news/comment/batch') {
                return array (  '_controller' => 'Sonata\\NewsBundle\\Controller\\CommentAdminController::batchAction',  '_sonata_admin' => 'sonata.news.admin.comment',  '_sonata_name' => 'admin_sonata_news_comment_batch',  '_route' => 'admin_sonata_news_comment_batch',);
            }

            // admin_sonata_news_comment_edit
            if (0 === strpos($pathinfo, '/admin/sonata/news/comment') && preg_match('#^/admin/sonata/news/comment/(?<id>[^/]+)/edit$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sonata\\NewsBundle\\Controller\\CommentAdminController::editAction',  '_sonata_admin' => 'sonata.news.admin.comment',  '_sonata_name' => 'admin_sonata_news_comment_edit',)), array('_route' => 'admin_sonata_news_comment_edit'));
            }

            // admin_sonata_news_comment_delete
            if (0 === strpos($pathinfo, '/admin/sonata/news/comment') && preg_match('#^/admin/sonata/news/comment/(?<id>[^/]+)/delete$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sonata\\NewsBundle\\Controller\\CommentAdminController::deleteAction',  '_sonata_admin' => 'sonata.news.admin.comment',  '_sonata_name' => 'admin_sonata_news_comment_delete',)), array('_route' => 'admin_sonata_news_comment_delete'));
            }

            // admin_sonata_news_comment_show
            if (0 === strpos($pathinfo, '/admin/sonata/news/comment') && preg_match('#^/admin/sonata/news/comment/(?<id>[^/]+)/show$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sonata\\NewsBundle\\Controller\\CommentAdminController::showAction',  '_sonata_admin' => 'sonata.news.admin.comment',  '_sonata_name' => 'admin_sonata_news_comment_show',)), array('_route' => 'admin_sonata_news_comment_show'));
            }

            // admin_sonata_news_comment_export
            if ($pathinfo === '/admin/sonata/news/comment/export') {
                return array (  '_controller' => 'Sonata\\NewsBundle\\Controller\\CommentAdminController::exportAction',  '_sonata_admin' => 'sonata.news.admin.comment',  '_sonata_name' => 'admin_sonata_news_comment_export',  '_route' => 'admin_sonata_news_comment_export',);
            }

            // admin_sonata_news_category_list
            if ($pathinfo === '/admin/sonata/news/category/list') {
                return array (  '_controller' => 'Sonata\\AdminBundle\\Controller\\CRUDController::listAction',  '_sonata_admin' => 'sonata.news.admin.category',  '_sonata_name' => 'admin_sonata_news_category_list',  '_route' => 'admin_sonata_news_category_list',);
            }

            // admin_sonata_news_category_create
            if ($pathinfo === '/admin/sonata/news/category/create') {
                return array (  '_controller' => 'Sonata\\AdminBundle\\Controller\\CRUDController::createAction',  '_sonata_admin' => 'sonata.news.admin.category',  '_sonata_name' => 'admin_sonata_news_category_create',  '_route' => 'admin_sonata_news_category_create',);
            }

            // admin_sonata_news_category_batch
            if ($pathinfo === '/admin/sonata/news/category/batch') {
                return array (  '_controller' => 'Sonata\\AdminBundle\\Controller\\CRUDController::batchAction',  '_sonata_admin' => 'sonata.news.admin.category',  '_sonata_name' => 'admin_sonata_news_category_batch',  '_route' => 'admin_sonata_news_category_batch',);
            }

            // admin_sonata_news_category_edit
            if (0 === strpos($pathinfo, '/admin/sonata/news/category') && preg_match('#^/admin/sonata/news/category/(?<id>[^/]+)/edit$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sonata\\AdminBundle\\Controller\\CRUDController::editAction',  '_sonata_admin' => 'sonata.news.admin.category',  '_sonata_name' => 'admin_sonata_news_category_edit',)), array('_route' => 'admin_sonata_news_category_edit'));
            }

            // admin_sonata_news_category_delete
            if (0 === strpos($pathinfo, '/admin/sonata/news/category') && preg_match('#^/admin/sonata/news/category/(?<id>[^/]+)/delete$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sonata\\AdminBundle\\Controller\\CRUDController::deleteAction',  '_sonata_admin' => 'sonata.news.admin.category',  '_sonata_name' => 'admin_sonata_news_category_delete',)), array('_route' => 'admin_sonata_news_category_delete'));
            }

            // admin_sonata_news_category_show
            if (0 === strpos($pathinfo, '/admin/sonata/news/category') && preg_match('#^/admin/sonata/news/category/(?<id>[^/]+)/show$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sonata\\AdminBundle\\Controller\\CRUDController::showAction',  '_sonata_admin' => 'sonata.news.admin.category',  '_sonata_name' => 'admin_sonata_news_category_show',)), array('_route' => 'admin_sonata_news_category_show'));
            }

            // admin_sonata_news_category_export
            if ($pathinfo === '/admin/sonata/news/category/export') {
                return array (  '_controller' => 'Sonata\\AdminBundle\\Controller\\CRUDController::exportAction',  '_sonata_admin' => 'sonata.news.admin.category',  '_sonata_name' => 'admin_sonata_news_category_export',  '_route' => 'admin_sonata_news_category_export',);
            }

            // admin_sonata_news_tag_list
            if ($pathinfo === '/admin/sonata/news/tag/list') {
                return array (  '_controller' => 'Sonata\\AdminBundle\\Controller\\CRUDController::listAction',  '_sonata_admin' => 'sonata.news.admin.tag',  '_sonata_name' => 'admin_sonata_news_tag_list',  '_route' => 'admin_sonata_news_tag_list',);
            }

            // admin_sonata_news_tag_create
            if ($pathinfo === '/admin/sonata/news/tag/create') {
                return array (  '_controller' => 'Sonata\\AdminBundle\\Controller\\CRUDController::createAction',  '_sonata_admin' => 'sonata.news.admin.tag',  '_sonata_name' => 'admin_sonata_news_tag_create',  '_route' => 'admin_sonata_news_tag_create',);
            }

            // admin_sonata_news_tag_batch
            if ($pathinfo === '/admin/sonata/news/tag/batch') {
                return array (  '_controller' => 'Sonata\\AdminBundle\\Controller\\CRUDController::batchAction',  '_sonata_admin' => 'sonata.news.admin.tag',  '_sonata_name' => 'admin_sonata_news_tag_batch',  '_route' => 'admin_sonata_news_tag_batch',);
            }

            // admin_sonata_news_tag_edit
            if (0 === strpos($pathinfo, '/admin/sonata/news/tag') && preg_match('#^/admin/sonata/news/tag/(?<id>[^/]+)/edit$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sonata\\AdminBundle\\Controller\\CRUDController::editAction',  '_sonata_admin' => 'sonata.news.admin.tag',  '_sonata_name' => 'admin_sonata_news_tag_edit',)), array('_route' => 'admin_sonata_news_tag_edit'));
            }

            // admin_sonata_news_tag_delete
            if (0 === strpos($pathinfo, '/admin/sonata/news/tag') && preg_match('#^/admin/sonata/news/tag/(?<id>[^/]+)/delete$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sonata\\AdminBundle\\Controller\\CRUDController::deleteAction',  '_sonata_admin' => 'sonata.news.admin.tag',  '_sonata_name' => 'admin_sonata_news_tag_delete',)), array('_route' => 'admin_sonata_news_tag_delete'));
            }

            // admin_sonata_news_tag_show
            if (0 === strpos($pathinfo, '/admin/sonata/news/tag') && preg_match('#^/admin/sonata/news/tag/(?<id>[^/]+)/show$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sonata\\AdminBundle\\Controller\\CRUDController::showAction',  '_sonata_admin' => 'sonata.news.admin.tag',  '_sonata_name' => 'admin_sonata_news_tag_show',)), array('_route' => 'admin_sonata_news_tag_show'));
            }

            // admin_sonata_news_tag_export
            if ($pathinfo === '/admin/sonata/news/tag/export') {
                return array (  '_controller' => 'Sonata\\AdminBundle\\Controller\\CRUDController::exportAction',  '_sonata_admin' => 'sonata.news.admin.tag',  '_sonata_name' => 'admin_sonata_news_tag_export',  '_route' => 'admin_sonata_news_tag_export',);
            }

            // admin_sonata_media_media_list
            if ($pathinfo === '/admin/sonata/media/media/list') {
                return array (  '_controller' => 'Sonata\\MediaBundle\\Controller\\MediaAdminController::listAction',  '_sonata_admin' => 'sonata.media.admin.media',  '_sonata_name' => 'admin_sonata_media_media_list',  '_route' => 'admin_sonata_media_media_list',);
            }

            // admin_sonata_media_media_create
            if ($pathinfo === '/admin/sonata/media/media/create') {
                return array (  '_controller' => 'Sonata\\MediaBundle\\Controller\\MediaAdminController::createAction',  '_sonata_admin' => 'sonata.media.admin.media',  '_sonata_name' => 'admin_sonata_media_media_create',  '_route' => 'admin_sonata_media_media_create',);
            }

            // admin_sonata_media_media_batch
            if ($pathinfo === '/admin/sonata/media/media/batch') {
                return array (  '_controller' => 'Sonata\\MediaBundle\\Controller\\MediaAdminController::batchAction',  '_sonata_admin' => 'sonata.media.admin.media',  '_sonata_name' => 'admin_sonata_media_media_batch',  '_route' => 'admin_sonata_media_media_batch',);
            }

            // admin_sonata_media_media_edit
            if (0 === strpos($pathinfo, '/admin/sonata/media/media') && preg_match('#^/admin/sonata/media/media/(?<id>[^/]+)/edit$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sonata\\MediaBundle\\Controller\\MediaAdminController::editAction',  '_sonata_admin' => 'sonata.media.admin.media',  '_sonata_name' => 'admin_sonata_media_media_edit',)), array('_route' => 'admin_sonata_media_media_edit'));
            }

            // admin_sonata_media_media_delete
            if (0 === strpos($pathinfo, '/admin/sonata/media/media') && preg_match('#^/admin/sonata/media/media/(?<id>[^/]+)/delete$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sonata\\MediaBundle\\Controller\\MediaAdminController::deleteAction',  '_sonata_admin' => 'sonata.media.admin.media',  '_sonata_name' => 'admin_sonata_media_media_delete',)), array('_route' => 'admin_sonata_media_media_delete'));
            }

            // admin_sonata_media_media_show
            if (0 === strpos($pathinfo, '/admin/sonata/media/media') && preg_match('#^/admin/sonata/media/media/(?<id>[^/]+)/show$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sonata\\MediaBundle\\Controller\\MediaAdminController::viewAction',  '_sonata_admin' => 'sonata.media.admin.media',  '_sonata_name' => 'admin_sonata_media_media_show',)), array('_route' => 'admin_sonata_media_media_show'));
            }

            // admin_sonata_media_media_export
            if ($pathinfo === '/admin/sonata/media/media/export') {
                return array (  '_controller' => 'Sonata\\MediaBundle\\Controller\\MediaAdminController::exportAction',  '_sonata_admin' => 'sonata.media.admin.media',  '_sonata_name' => 'admin_sonata_media_media_export',  '_route' => 'admin_sonata_media_media_export',);
            }

            // admin_sonata_media_media_view
            if (0 === strpos($pathinfo, '/admin/sonata/media/media') && preg_match('#^/admin/sonata/media/media/(?<id>[^/]+)/view$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sonata\\MediaBundle\\Controller\\MediaAdminController::viewAction',  '_sonata_admin' => 'sonata.media.admin.media',  '_sonata_name' => 'admin_sonata_media_media_view',)), array('_route' => 'admin_sonata_media_media_view'));
            }

            // admin_sonata_media_gallery_list
            if ($pathinfo === '/admin/sonata/media/gallery/list') {
                return array (  '_controller' => 'Sonata\\MediaBundle\\Controller\\GalleryAdminController::listAction',  '_sonata_admin' => 'sonata.media.admin.gallery',  '_sonata_name' => 'admin_sonata_media_gallery_list',  '_route' => 'admin_sonata_media_gallery_list',);
            }

            // admin_sonata_media_gallery_create
            if ($pathinfo === '/admin/sonata/media/gallery/create') {
                return array (  '_controller' => 'Sonata\\MediaBundle\\Controller\\GalleryAdminController::createAction',  '_sonata_admin' => 'sonata.media.admin.gallery',  '_sonata_name' => 'admin_sonata_media_gallery_create',  '_route' => 'admin_sonata_media_gallery_create',);
            }

            // admin_sonata_media_gallery_batch
            if ($pathinfo === '/admin/sonata/media/gallery/batch') {
                return array (  '_controller' => 'Sonata\\MediaBundle\\Controller\\GalleryAdminController::batchAction',  '_sonata_admin' => 'sonata.media.admin.gallery',  '_sonata_name' => 'admin_sonata_media_gallery_batch',  '_route' => 'admin_sonata_media_gallery_batch',);
            }

            // admin_sonata_media_gallery_edit
            if (0 === strpos($pathinfo, '/admin/sonata/media/gallery') && preg_match('#^/admin/sonata/media/gallery/(?<id>[^/]+)/edit$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sonata\\MediaBundle\\Controller\\GalleryAdminController::editAction',  '_sonata_admin' => 'sonata.media.admin.gallery',  '_sonata_name' => 'admin_sonata_media_gallery_edit',)), array('_route' => 'admin_sonata_media_gallery_edit'));
            }

            // admin_sonata_media_gallery_delete
            if (0 === strpos($pathinfo, '/admin/sonata/media/gallery') && preg_match('#^/admin/sonata/media/gallery/(?<id>[^/]+)/delete$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sonata\\MediaBundle\\Controller\\GalleryAdminController::deleteAction',  '_sonata_admin' => 'sonata.media.admin.gallery',  '_sonata_name' => 'admin_sonata_media_gallery_delete',)), array('_route' => 'admin_sonata_media_gallery_delete'));
            }

            // admin_sonata_media_gallery_show
            if (0 === strpos($pathinfo, '/admin/sonata/media/gallery') && preg_match('#^/admin/sonata/media/gallery/(?<id>[^/]+)/show$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sonata\\MediaBundle\\Controller\\GalleryAdminController::showAction',  '_sonata_admin' => 'sonata.media.admin.gallery',  '_sonata_name' => 'admin_sonata_media_gallery_show',)), array('_route' => 'admin_sonata_media_gallery_show'));
            }

            // admin_sonata_media_gallery_export
            if ($pathinfo === '/admin/sonata/media/gallery/export') {
                return array (  '_controller' => 'Sonata\\MediaBundle\\Controller\\GalleryAdminController::exportAction',  '_sonata_admin' => 'sonata.media.admin.gallery',  '_sonata_name' => 'admin_sonata_media_gallery_export',  '_route' => 'admin_sonata_media_gallery_export',);
            }

            // admin_sonata_media_galleryhasmedia_list
            if ($pathinfo === '/admin/sonata/media/galleryhasmedia/list') {
                return array (  '_controller' => 'Sonata\\AdminBundle\\Controller\\CRUDController::listAction',  '_sonata_admin' => 'sonata.media.admin.gallery_has_media',  '_sonata_name' => 'admin_sonata_media_galleryhasmedia_list',  '_route' => 'admin_sonata_media_galleryhasmedia_list',);
            }

            // admin_sonata_media_galleryhasmedia_create
            if ($pathinfo === '/admin/sonata/media/galleryhasmedia/create') {
                return array (  '_controller' => 'Sonata\\AdminBundle\\Controller\\CRUDController::createAction',  '_sonata_admin' => 'sonata.media.admin.gallery_has_media',  '_sonata_name' => 'admin_sonata_media_galleryhasmedia_create',  '_route' => 'admin_sonata_media_galleryhasmedia_create',);
            }

            // admin_sonata_media_galleryhasmedia_batch
            if ($pathinfo === '/admin/sonata/media/galleryhasmedia/batch') {
                return array (  '_controller' => 'Sonata\\AdminBundle\\Controller\\CRUDController::batchAction',  '_sonata_admin' => 'sonata.media.admin.gallery_has_media',  '_sonata_name' => 'admin_sonata_media_galleryhasmedia_batch',  '_route' => 'admin_sonata_media_galleryhasmedia_batch',);
            }

            // admin_sonata_media_galleryhasmedia_edit
            if (0 === strpos($pathinfo, '/admin/sonata/media/galleryhasmedia') && preg_match('#^/admin/sonata/media/galleryhasmedia/(?<id>[^/]+)/edit$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sonata\\AdminBundle\\Controller\\CRUDController::editAction',  '_sonata_admin' => 'sonata.media.admin.gallery_has_media',  '_sonata_name' => 'admin_sonata_media_galleryhasmedia_edit',)), array('_route' => 'admin_sonata_media_galleryhasmedia_edit'));
            }

            // admin_sonata_media_galleryhasmedia_delete
            if (0 === strpos($pathinfo, '/admin/sonata/media/galleryhasmedia') && preg_match('#^/admin/sonata/media/galleryhasmedia/(?<id>[^/]+)/delete$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sonata\\AdminBundle\\Controller\\CRUDController::deleteAction',  '_sonata_admin' => 'sonata.media.admin.gallery_has_media',  '_sonata_name' => 'admin_sonata_media_galleryhasmedia_delete',)), array('_route' => 'admin_sonata_media_galleryhasmedia_delete'));
            }

            // admin_sonata_media_galleryhasmedia_show
            if (0 === strpos($pathinfo, '/admin/sonata/media/galleryhasmedia') && preg_match('#^/admin/sonata/media/galleryhasmedia/(?<id>[^/]+)/show$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sonata\\AdminBundle\\Controller\\CRUDController::showAction',  '_sonata_admin' => 'sonata.media.admin.gallery_has_media',  '_sonata_name' => 'admin_sonata_media_galleryhasmedia_show',)), array('_route' => 'admin_sonata_media_galleryhasmedia_show'));
            }

            // admin_sonata_media_galleryhasmedia_export
            if ($pathinfo === '/admin/sonata/media/galleryhasmedia/export') {
                return array (  '_controller' => 'Sonata\\AdminBundle\\Controller\\CRUDController::exportAction',  '_sonata_admin' => 'sonata.media.admin.gallery_has_media',  '_sonata_name' => 'admin_sonata_media_galleryhasmedia_export',  '_route' => 'admin_sonata_media_galleryhasmedia_export',);
            }

            // admin_tperroin_tremplinfoyer_association_list
            if ($pathinfo === '/admin/tperroin/tremplinfoyer/association/list') {
                return array (  '_controller' => 'Tperroin\\TremplinFoyerBundle\\Controller\\AssociationAdminController::listAction',  '_sonata_admin' => 'tperroin_tremplin_foyer.association',  '_sonata_name' => 'admin_tperroin_tremplinfoyer_association_list',  '_route' => 'admin_tperroin_tremplinfoyer_association_list',);
            }

            // admin_tperroin_tremplinfoyer_association_create
            if ($pathinfo === '/admin/tperroin/tremplinfoyer/association/create') {
                return array (  '_controller' => 'Tperroin\\TremplinFoyerBundle\\Controller\\AssociationAdminController::createAction',  '_sonata_admin' => 'tperroin_tremplin_foyer.association',  '_sonata_name' => 'admin_tperroin_tremplinfoyer_association_create',  '_route' => 'admin_tperroin_tremplinfoyer_association_create',);
            }

            // admin_tperroin_tremplinfoyer_association_batch
            if ($pathinfo === '/admin/tperroin/tremplinfoyer/association/batch') {
                return array (  '_controller' => 'Tperroin\\TremplinFoyerBundle\\Controller\\AssociationAdminController::batchAction',  '_sonata_admin' => 'tperroin_tremplin_foyer.association',  '_sonata_name' => 'admin_tperroin_tremplinfoyer_association_batch',  '_route' => 'admin_tperroin_tremplinfoyer_association_batch',);
            }

            // admin_tperroin_tremplinfoyer_association_edit
            if (0 === strpos($pathinfo, '/admin/tperroin/tremplinfoyer/association') && preg_match('#^/admin/tperroin/tremplinfoyer/association/(?<id>[^/]+)/edit$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Tperroin\\TremplinFoyerBundle\\Controller\\AssociationAdminController::editAction',  '_sonata_admin' => 'tperroin_tremplin_foyer.association',  '_sonata_name' => 'admin_tperroin_tremplinfoyer_association_edit',)), array('_route' => 'admin_tperroin_tremplinfoyer_association_edit'));
            }

            // admin_tperroin_tremplinfoyer_association_delete
            if (0 === strpos($pathinfo, '/admin/tperroin/tremplinfoyer/association') && preg_match('#^/admin/tperroin/tremplinfoyer/association/(?<id>[^/]+)/delete$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Tperroin\\TremplinFoyerBundle\\Controller\\AssociationAdminController::deleteAction',  '_sonata_admin' => 'tperroin_tremplin_foyer.association',  '_sonata_name' => 'admin_tperroin_tremplinfoyer_association_delete',)), array('_route' => 'admin_tperroin_tremplinfoyer_association_delete'));
            }

            // admin_tperroin_tremplinfoyer_association_show
            if (0 === strpos($pathinfo, '/admin/tperroin/tremplinfoyer/association') && preg_match('#^/admin/tperroin/tremplinfoyer/association/(?<id>[^/]+)/show$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Tperroin\\TremplinFoyerBundle\\Controller\\AssociationAdminController::showAction',  '_sonata_admin' => 'tperroin_tremplin_foyer.association',  '_sonata_name' => 'admin_tperroin_tremplinfoyer_association_show',)), array('_route' => 'admin_tperroin_tremplinfoyer_association_show'));
            }

            // admin_tperroin_tremplinfoyer_association_export
            if ($pathinfo === '/admin/tperroin/tremplinfoyer/association/export') {
                return array (  '_controller' => 'Tperroin\\TremplinFoyerBundle\\Controller\\AssociationAdminController::exportAction',  '_sonata_admin' => 'tperroin_tremplin_foyer.association',  '_sonata_name' => 'admin_tperroin_tremplinfoyer_association_export',  '_route' => 'admin_tperroin_tremplinfoyer_association_export',);
            }

            // admin_tperroin_tremplinfoyer_tremplin_list
            if ($pathinfo === '/admin/tperroin/tremplinfoyer/tremplin/list') {
                return array (  '_controller' => 'Tperroin\\TremplinFoyerBundle\\Controller\\TremplinAdminController::listAction',  '_sonata_admin' => 'tperroin_tremplin_foyer.tremplin',  '_sonata_name' => 'admin_tperroin_tremplinfoyer_tremplin_list',  '_route' => 'admin_tperroin_tremplinfoyer_tremplin_list',);
            }

            // admin_tperroin_tremplinfoyer_tremplin_create
            if ($pathinfo === '/admin/tperroin/tremplinfoyer/tremplin/create') {
                return array (  '_controller' => 'Tperroin\\TremplinFoyerBundle\\Controller\\TremplinAdminController::createAction',  '_sonata_admin' => 'tperroin_tremplin_foyer.tremplin',  '_sonata_name' => 'admin_tperroin_tremplinfoyer_tremplin_create',  '_route' => 'admin_tperroin_tremplinfoyer_tremplin_create',);
            }

            // admin_tperroin_tremplinfoyer_tremplin_batch
            if ($pathinfo === '/admin/tperroin/tremplinfoyer/tremplin/batch') {
                return array (  '_controller' => 'Tperroin\\TremplinFoyerBundle\\Controller\\TremplinAdminController::batchAction',  '_sonata_admin' => 'tperroin_tremplin_foyer.tremplin',  '_sonata_name' => 'admin_tperroin_tremplinfoyer_tremplin_batch',  '_route' => 'admin_tperroin_tremplinfoyer_tremplin_batch',);
            }

            // admin_tperroin_tremplinfoyer_tremplin_edit
            if (0 === strpos($pathinfo, '/admin/tperroin/tremplinfoyer/tremplin') && preg_match('#^/admin/tperroin/tremplinfoyer/tremplin/(?<id>[^/]+)/edit$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Tperroin\\TremplinFoyerBundle\\Controller\\TremplinAdminController::editAction',  '_sonata_admin' => 'tperroin_tremplin_foyer.tremplin',  '_sonata_name' => 'admin_tperroin_tremplinfoyer_tremplin_edit',)), array('_route' => 'admin_tperroin_tremplinfoyer_tremplin_edit'));
            }

            // admin_tperroin_tremplinfoyer_tremplin_delete
            if (0 === strpos($pathinfo, '/admin/tperroin/tremplinfoyer/tremplin') && preg_match('#^/admin/tperroin/tremplinfoyer/tremplin/(?<id>[^/]+)/delete$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Tperroin\\TremplinFoyerBundle\\Controller\\TremplinAdminController::deleteAction',  '_sonata_admin' => 'tperroin_tremplin_foyer.tremplin',  '_sonata_name' => 'admin_tperroin_tremplinfoyer_tremplin_delete',)), array('_route' => 'admin_tperroin_tremplinfoyer_tremplin_delete'));
            }

            // admin_tperroin_tremplinfoyer_tremplin_show
            if (0 === strpos($pathinfo, '/admin/tperroin/tremplinfoyer/tremplin') && preg_match('#^/admin/tperroin/tremplinfoyer/tremplin/(?<id>[^/]+)/show$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Tperroin\\TremplinFoyerBundle\\Controller\\TremplinAdminController::showAction',  '_sonata_admin' => 'tperroin_tremplin_foyer.tremplin',  '_sonata_name' => 'admin_tperroin_tremplinfoyer_tremplin_show',)), array('_route' => 'admin_tperroin_tremplinfoyer_tremplin_show'));
            }

            // admin_tperroin_tremplinfoyer_tremplin_export
            if ($pathinfo === '/admin/tperroin/tremplinfoyer/tremplin/export') {
                return array (  '_controller' => 'Tperroin\\TremplinFoyerBundle\\Controller\\TremplinAdminController::exportAction',  '_sonata_admin' => 'tperroin_tremplin_foyer.tremplin',  '_sonata_name' => 'admin_tperroin_tremplinfoyer_tremplin_export',  '_route' => 'admin_tperroin_tremplinfoyer_tremplin_export',);
            }

        }

        // homepage
        if (rtrim($pathinfo, '/') === '') {
            if (substr($pathinfo, -1) !== '/') {
                return $this->redirect($pathinfo.'/', 'homepage');
            }

            return array (  '_controller' => 'Tperroin\\TremplinFoyerBundle\\Controller\\DefaultController::indexAction',  '_route' => 'homepage',);
        }

        // fos_user_security_login
        if ($pathinfo === '/login') {
            return array (  '_controller' => 'FOS\\UserBundle\\Controller\\SecurityController::loginAction',  '_route' => 'fos_user_security_login',);
        }

        // fos_user_security_check
        if ($pathinfo === '/login_check') {
            return array (  '_controller' => 'FOS\\UserBundle\\Controller\\SecurityController::checkAction',  '_route' => 'fos_user_security_check',);
        }

        // fos_user_security_logout
        if ($pathinfo === '/logout') {
            return array (  '_controller' => 'FOS\\UserBundle\\Controller\\SecurityController::logoutAction',  '_route' => 'fos_user_security_logout',);
        }

        if (0 === strpos($pathinfo, '/profile')) {
            // sonata_user_profile_show
            if (rtrim($pathinfo, '/') === '/profile') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_sonata_user_profile_show;
                }

                if (substr($pathinfo, -1) !== '/') {
                    return $this->redirect($pathinfo.'/', 'sonata_user_profile_show');
                }

                return array (  '_controller' => 'Sonata\\UserBundle\\Controller\\ProfileController::showAction',  '_route' => 'sonata_user_profile_show',);
            }
            not_sonata_user_profile_show:

            // sonata_user_profile_edit_authentication
            if ($pathinfo === '/profile/edit-authentication') {
                return array (  '_controller' => 'Sonata\\UserBundle\\Controller\\ProfileController::editAuthenticationAction',  '_route' => 'sonata_user_profile_edit_authentication',);
            }

            // sonata_user_profile_edit
            if ($pathinfo === '/profile/edit-profile') {
                return array (  '_controller' => 'Sonata\\UserBundle\\Controller\\ProfileController::editProfileAction',  '_route' => 'sonata_user_profile_edit',);
            }

        }

        if (0 === strpos($pathinfo, '/register')) {
            // fos_user_registration_register
            if (rtrim($pathinfo, '/') === '/register') {
                if (substr($pathinfo, -1) !== '/') {
                    return $this->redirect($pathinfo.'/', 'fos_user_registration_register');
                }

                return array (  '_controller' => 'FOS\\UserBundle\\Controller\\RegistrationController::registerAction',  '_route' => 'fos_user_registration_register',);
            }

            // fos_user_registration_check_email
            if ($pathinfo === '/register/check-email') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_fos_user_registration_check_email;
                }

                return array (  '_controller' => 'FOS\\UserBundle\\Controller\\RegistrationController::checkEmailAction',  '_route' => 'fos_user_registration_check_email',);
            }
            not_fos_user_registration_check_email:

            // fos_user_registration_confirm
            if (0 === strpos($pathinfo, '/register/confirm') && preg_match('#^/register/confirm/(?<token>[^/]+)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_fos_user_registration_confirm;
                }

                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'FOS\\UserBundle\\Controller\\RegistrationController::confirmAction',)), array('_route' => 'fos_user_registration_confirm'));
            }
            not_fos_user_registration_confirm:

            // fos_user_registration_confirmed
            if ($pathinfo === '/register/confirmed') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_fos_user_registration_confirmed;
                }

                return array (  '_controller' => 'FOS\\UserBundle\\Controller\\RegistrationController::confirmedAction',  '_route' => 'fos_user_registration_confirmed',);
            }
            not_fos_user_registration_confirmed:

        }

        if (0 === strpos($pathinfo, '/resetting')) {
            // fos_user_resetting_request
            if ($pathinfo === '/resetting/request') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_fos_user_resetting_request;
                }

                return array (  '_controller' => 'FOS\\UserBundle\\Controller\\ResettingController::requestAction',  '_route' => 'fos_user_resetting_request',);
            }
            not_fos_user_resetting_request:

            // fos_user_resetting_send_email
            if ($pathinfo === '/resetting/send-email') {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_fos_user_resetting_send_email;
                }

                return array (  '_controller' => 'FOS\\UserBundle\\Controller\\ResettingController::sendEmailAction',  '_route' => 'fos_user_resetting_send_email',);
            }
            not_fos_user_resetting_send_email:

            // fos_user_resetting_check_email
            if ($pathinfo === '/resetting/check-email') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_fos_user_resetting_check_email;
                }

                return array (  '_controller' => 'FOS\\UserBundle\\Controller\\ResettingController::checkEmailAction',  '_route' => 'fos_user_resetting_check_email',);
            }
            not_fos_user_resetting_check_email:

            // fos_user_resetting_reset
            if (0 === strpos($pathinfo, '/resetting/reset') && preg_match('#^/resetting/reset/(?<token>[^/]+)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'POST', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'POST', 'HEAD'));
                    goto not_fos_user_resetting_reset;
                }

                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'FOS\\UserBundle\\Controller\\ResettingController::resetAction',)), array('_route' => 'fos_user_resetting_reset'));
            }
            not_fos_user_resetting_reset:

        }

        // fos_user_change_password
        if ($pathinfo === '/change-password/change-password') {
            if (!in_array($this->context->getMethod(), array('GET', 'POST', 'HEAD'))) {
                $allow = array_merge($allow, array('GET', 'POST', 'HEAD'));
                goto not_fos_user_change_password;
            }

            return array (  '_controller' => 'FOS\\UserBundle\\Controller\\ChangePasswordController::changePasswordAction',  '_route' => 'fos_user_change_password',);
        }
        not_fos_user_change_password:

        if (0 === strpos($pathinfo, '/admin')) {
            // sonata_user_admin_security_login
            if ($pathinfo === '/admin/login') {
                return array (  '_controller' => 'Sonata\\UserBundle\\Controller\\AdminSecurityController::loginAction',  '_route' => 'sonata_user_admin_security_login',);
            }

            // sonata_user_admin_security_check
            if ($pathinfo === '/admin/login_check') {
                return array (  '_controller' => 'Sonata\\UserBundle\\Controller\\AdminSecurityController::checkAction',  '_route' => 'sonata_user_admin_security_check',);
            }

            // sonata_user_admin_security_logout
            if ($pathinfo === '/admin/logout') {
                return array (  '_controller' => 'Sonata\\UserBundle\\Controller\\AdminSecurityController::logoutAction',  '_route' => 'sonata_user_admin_security_logout',);
            }

        }

        if (0 === strpos($pathinfo, '/media/gallery')) {
            // sonata_media_gallery_index
            if (rtrim($pathinfo, '/') === '/media/gallery') {
                if (substr($pathinfo, -1) !== '/') {
                    return $this->redirect($pathinfo.'/', 'sonata_media_gallery_index');
                }

                return array (  '_controller' => 'Sonata\\MediaBundle\\Controller\\GalleryController::indexAction',  '_route' => 'sonata_media_gallery_index',);
            }

            // sonata_media_gallery_view
            if (0 === strpos($pathinfo, '/media/gallery/view') && preg_match('#^/media/gallery/view/(?<id>[^/]+)$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sonata\\MediaBundle\\Controller\\GalleryController::viewAction',)), array('_route' => 'sonata_media_gallery_view'));
            }

        }

        if (0 === strpos($pathinfo, '/media')) {
            // sonata_media_view
            if (0 === strpos($pathinfo, '/media/view') && preg_match('#^/media/view/(?<id>[^/]+)(?:/(?<format>[^/]+))?$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sonata\\MediaBundle\\Controller\\MediaController::viewAction',  'format' => 'reference',)), array('_route' => 'sonata_media_view'));
            }

            // sonata_media_download
            if (0 === strpos($pathinfo, '/media/download') && preg_match('#^/media/download/(?<id>[^/]+)(?:/(?<format>[^/]+))?$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sonata\\MediaBundle\\Controller\\MediaController::downloadAction',  'format' => 'reference',)), array('_route' => 'sonata_media_download'));
            }

        }

        if (0 === strpos($pathinfo, '/news')) {
            // sonata_news_add_comment
            if (0 === strpos($pathinfo, '/news/add-comment') && preg_match('#^/news/add\\-comment/(?<id>[^/]+)$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sonata\\NewsBundle\\Controller\\PostController::addCommentAction',)), array('_route' => 'sonata_news_add_comment'));
            }

            // sonata_news_archive_monthly
            if (0 === strpos($pathinfo, '/news/archive') && preg_match('#^/news/archive/(?<year>\\d+)/(?<month>\\d+)(?:\\.(?<_format>html|rss))?$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sonata\\NewsBundle\\Controller\\PostController::archiveMonthlyAction',  '_format' => 'html',)), array('_route' => 'sonata_news_archive_monthly'));
            }

            // sonata_news_tag
            if (0 === strpos($pathinfo, '/news/tag') && preg_match('#^/news/tag/(?<tag>[^/\\.]+)(?:\\.(?<_format>html|rss))?$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sonata\\NewsBundle\\Controller\\PostController::tagAction',  '_format' => 'html',)), array('_route' => 'sonata_news_tag'));
            }

            // sonata_news_category
            if (0 === strpos($pathinfo, '/news/category') && preg_match('#^/news/category/(?<category>[^/\\.]+)(?:\\.(?<_format>html|rss))?$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sonata\\NewsBundle\\Controller\\PostController::categoryAction',  '_format' => 'html',)), array('_route' => 'sonata_news_category'));
            }

            // sonata_news_archive_yearly
            if (0 === strpos($pathinfo, '/news/archive') && preg_match('#^/news/archive/(?<year>\\d+)(?:\\.(?<_format>html|rss))?$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sonata\\NewsBundle\\Controller\\PostController::archiveYearlyAction',  '_format' => 'html',)), array('_route' => 'sonata_news_archive_yearly'));
            }

            // sonata_news_archive
            if (0 === strpos($pathinfo, '/news/archive') && preg_match('#^/news/archive(?:\\.(?<_format>html|rss))?$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sonata\\NewsBundle\\Controller\\PostController::archiveAction',  '_format' => 'html',)), array('_route' => 'sonata_news_archive'));
            }

            // sonata_news_comment_moderation
            if (0 === strpos($pathinfo, '/news/comment/moderation') && preg_match('#^/news/comment/moderation/(?<commentId>[^/]+)/(?<hash>[^/]+)/(?<status>[^/]+)$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sonata\\NewsBundle\\Controller\\PostController::commentModerationAction',)), array('_route' => 'sonata_news_comment_moderation'));
            }

            // sonata_news_view
            if (preg_match('#^/news/(?<permalink>.+?)(?:\\.(?<_format>html|rss))?$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Sonata\\NewsBundle\\Controller\\PostController::viewAction',  '_format' => 'html',)), array('_route' => 'sonata_news_view'));
            }

            // sonata_news_home
            if (rtrim($pathinfo, '/') === '/news') {
                if (substr($pathinfo, -1) !== '/') {
                    return $this->redirect($pathinfo.'/', 'sonata_news_home');
                }

                return array (  '_controller' => 'Sonata\\NewsBundle\\Controller\\PostController::homeAction',  '_route' => 'sonata_news_home',);
            }

        }

        if (0 === strpos($pathinfo, '/association')) {
            // association
            if (rtrim($pathinfo, '/') === '/association') {
                if (substr($pathinfo, -1) !== '/') {
                    return $this->redirect($pathinfo.'/', 'association');
                }

                return array (  '_controller' => 'Tperroin\\TremplinFoyerBundle\\Controller\\AssociationController::indexAction',  '_route' => 'association',);
            }

            // association_show
            if (preg_match('#^/association/(?<id>[^/]+)/show$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Tperroin\\TremplinFoyerBundle\\Controller\\AssociationController::showAction',)), array('_route' => 'association_show'));
            }

            // association_new
            if ($pathinfo === '/association/new') {
                return array (  '_controller' => 'Tperroin\\TremplinFoyerBundle\\Controller\\AssociationController::newAction',  '_route' => 'association_new',);
            }

            // association_create
            if ($pathinfo === '/association/create') {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_association_create;
                }

                return array (  '_controller' => 'Tperroin\\TremplinFoyerBundle\\Controller\\AssociationController::createAction',  '_route' => 'association_create',);
            }
            not_association_create:

            // association_edit
            if (preg_match('#^/association/(?<id>[^/]+)/edit$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Tperroin\\TremplinFoyerBundle\\Controller\\AssociationController::editAction',)), array('_route' => 'association_edit'));
            }

            // association_update
            if (preg_match('#^/association/(?<id>[^/]+)/update$#s', $pathinfo, $matches)) {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_association_update;
                }

                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Tperroin\\TremplinFoyerBundle\\Controller\\AssociationController::updateAction',)), array('_route' => 'association_update'));
            }
            not_association_update:

            // association_delete
            if (preg_match('#^/association/(?<id>[^/]+)/delete$#s', $pathinfo, $matches)) {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_association_delete;
                }

                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Tperroin\\TremplinFoyerBundle\\Controller\\AssociationController::deleteAction',)), array('_route' => 'association_delete'));
            }
            not_association_delete:

        }

        // tremplin
        if (rtrim($pathinfo, '/') === '/tremplin') {
            if (substr($pathinfo, -1) !== '/') {
                return $this->redirect($pathinfo.'/', 'tremplin');
            }

            return array (  '_controller' => 'Tperroin\\TremplinFoyerBundle\\Controller\\TremplinController::indexAction',  '_route' => 'tremplin',);
        }

        // tremplin_show
        if (0 === strpos($pathinfo, '/tremplin') && preg_match('#^/tremplin/(?<id>[^/]+)/show$#s', $pathinfo, $matches)) {
            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Tperroin\\TremplinFoyerBundle\\Controller\\TremplinController::showAction',)), array('_route' => 'tremplin_show'));
        }

        // tremplin_new
        if ($pathinfo === '/tremplin/new') {
            return array (  '_controller' => 'Tperroin\\TremplinFoyerBundle\\Controller\\TremplinController::newAction',  '_route' => 'tremplin_new',);
        }

        // tremplin_create
        if ($pathinfo === '/tremplin/create') {
            if ($this->context->getMethod() != 'POST') {
                $allow[] = 'POST';
                goto not_tremplin_create;
            }

            return array (  '_controller' => 'Tperroin\\TremplinFoyerBundle\\Controller\\TremplinController::createAction',  '_route' => 'tremplin_create',);
        }
        not_tremplin_create:

        // tremplin_edit
        if (0 === strpos($pathinfo, '/tremplin') && preg_match('#^/tremplin/(?<id>[^/]+)/edit$#s', $pathinfo, $matches)) {
            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Tperroin\\TremplinFoyerBundle\\Controller\\TremplinController::editAction',)), array('_route' => 'tremplin_edit'));
        }

        // tremplin_update
        if (0 === strpos($pathinfo, '/tremplin') && preg_match('#^/tremplin/(?<id>[^/]+)/update$#s', $pathinfo, $matches)) {
            if ($this->context->getMethod() != 'POST') {
                $allow[] = 'POST';
                goto not_tremplin_update;
            }

            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Tperroin\\TremplinFoyerBundle\\Controller\\TremplinController::updateAction',)), array('_route' => 'tremplin_update'));
        }
        not_tremplin_update:

        // tremplin_delete
        if (0 === strpos($pathinfo, '/tremplin') && preg_match('#^/tremplin/(?<id>[^/]+)/delete$#s', $pathinfo, $matches)) {
            if ($this->context->getMethod() != 'POST') {
                $allow[] = 'POST';
                goto not_tremplin_delete;
            }

            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Tperroin\\TremplinFoyerBundle\\Controller\\TremplinController::deleteAction',)), array('_route' => 'tremplin_delete'));
        }
        not_tremplin_delete:

        throw 0 < count($allow) ? new MethodNotAllowedException(array_unique($allow)) : new ResourceNotFoundException();
    }
}
