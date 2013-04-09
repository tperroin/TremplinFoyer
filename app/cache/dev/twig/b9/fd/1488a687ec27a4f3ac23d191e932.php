<?php

/* SonataAdminBundle::standard_layout.html.twig */
class __TwigTemplate_b9fd1488a687ec27a4f3ac23d191e932 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'stylesheets' => array($this, 'block_stylesheets'),
            'javascripts' => array($this, 'block_javascripts'),
            'logo' => array($this, 'block_logo'),
            'top_bar_before_nav' => array($this, 'block_top_bar_before_nav'),
            'sonata_top_bar_nav' => array($this, 'block_sonata_top_bar_nav'),
            'top_bar_after_nav' => array($this, 'block_top_bar_after_nav'),
            'notice' => array($this, 'block_notice'),
            'actions' => array($this, 'block_actions'),
            'sonata_admin_content' => array($this, 'block_sonata_admin_content'),
            'footer' => array($this, 'block_footer'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 11
        $context["_preview"] = $this->renderBlock("preview", $context, $blocks);
        // line 12
        $context["_form"] = $this->renderBlock("form", $context, $blocks);
        // line 13
        $context["_show"] = $this->renderBlock("show", $context, $blocks);
        // line 14
        $context["_list_table"] = $this->renderBlock("list_table", $context, $blocks);
        // line 15
        $context["_list_filters"] = $this->renderBlock("list_filters", $context, $blocks);
        // line 16
        $context["_side_menu"] = $this->renderBlock("side_menu", $context, $blocks);
        // line 17
        $context["_content"] = $this->renderBlock("content", $context, $blocks);
        // line 18
        $context["_title"] = $this->renderBlock("title", $context, $blocks);
        // line 19
        $context["_breadcrumb"] = $this->renderBlock("breadcrumb", $context, $blocks);
        // line 20
        echo "<!DOCTYPE html>
<html class=\"no-js\">
    <head>
        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />

        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">

        ";
        // line 27
        $this->displayBlock('stylesheets', $context, $blocks);
        // line 38
        echo "
        ";
        // line 39
        $this->displayBlock('javascripts', $context, $blocks);
        // line 50
        echo "
        <title>
            ";
        // line 52
        echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("Admin", array(), "SonataAdminBundle"), "html", null, true);
        echo "

            ";
        // line 54
        if ((!twig_test_empty($this->getContext($context, "_title")))) {
            // line 55
            echo "                ";
            echo $this->getContext($context, "_title");
            echo "
            ";
        } else {
            // line 57
            echo "                ";
            if (array_key_exists("action", $context)) {
                // line 58
                echo "                    -
                    ";
                // line 59
                $context['_parent'] = (array) $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getContext($context, "admin"), "breadcrumbs", array(0 => $this->getContext($context, "action")), "method"));
                $context['loop'] = array(
                  'parent' => $context['_parent'],
                  'index0' => 0,
                  'index'  => 1,
                  'first'  => true,
                );
                if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof Countable)) {
                    $length = count($context['_seq']);
                    $context['loop']['revindex0'] = $length - 1;
                    $context['loop']['revindex'] = $length;
                    $context['loop']['length'] = $length;
                    $context['loop']['last'] = 1 === $length;
                }
                foreach ($context['_seq'] as $context["_key"] => $context["menu"]) {
                    // line 60
                    echo "                        ";
                    if ((!$this->getAttribute($this->getContext($context, "loop"), "first"))) {
                        // line 61
                        echo "                            &gt;
                        ";
                    }
                    // line 63
                    echo "                        ";
                    echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "menu"), "label"), "html", null, true);
                    echo "
                    ";
                    ++$context['loop']['index0'];
                    ++$context['loop']['index'];
                    $context['loop']['first'] = false;
                    if (isset($context['loop']['length'])) {
                        --$context['loop']['revindex0'];
                        --$context['loop']['revindex'];
                        $context['loop']['last'] = 0 === $context['loop']['revindex0'];
                    }
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['menu'], $context['_parent'], $context['loop']);
                $context = array_merge($_parent, array_intersect_key($context, $_parent));
                // line 65
                echo "                ";
            }
            // line 66
            echo "            ";
        }
        // line 67
        echo "        </title>
    </head>
    <body class=\"sonata-bc ";
        // line 69
        if (twig_test_empty($this->getContext($context, "_side_menu"))) {
            echo "sonata-ba-no-side-menu";
        }
        echo "\">
        ";
        // line 71
        echo "
        <div class=\"navbar navbar-fixed-top\">
            <div class=\"navbar-inner\">
                <div class=\"container-fluid\">
                    <a class=\"btn btn-navbar\" data-toggle=\"collapse\" data-target=\".nav-collapse\">
                        <span class=\"icon-bar\"></span>
                        <span class=\"icon-bar\"></span>
                        <span class=\"icon-bar\"></span>
                    </a>

                    ";
        // line 81
        if (array_key_exists("admin_pool", $context)) {
            // line 82
            echo "                        <div class=\"navbar-text pull-right\">";
            $template = $this->env->resolveTemplate($this->getAttribute($this->getContext($context, "admin_pool"), "getTemplate", array(0 => "user_block"), "method"));
            $template->display($context);
            echo "</div>

                        ";
            // line 84
            $this->displayBlock('logo', $context, $blocks);
            // line 90
            echo "
                        <div class=\"nav-collapse\">
                            <ul class=\"nav\">
                                ";
            // line 93
            $this->displayBlock('top_bar_before_nav', $context, $blocks);
            // line 94
            echo "                                ";
            $this->displayBlock('sonata_top_bar_nav', $context, $blocks);
            // line 110
            echo "                                ";
            $this->displayBlock('top_bar_after_nav', $context, $blocks);
            // line 111
            echo "                            </ul>
                        </div>
                    ";
        }
        // line 114
        echo "                </div>
            </div>
        </div>

        <div class=\"container-fluid\">
            ";
        // line 119
        $this->displayBlock('notice', $context, $blocks);
        // line 129
        echo "
            <div class=\"row-fluid\">
                <div class=\"span6\">
                    ";
        // line 132
        if (((!twig_test_empty($this->getContext($context, "_breadcrumb"))) || array_key_exists("action", $context))) {
            // line 133
            echo "                        <ul class=\"breadcrumb\">
                            ";
            // line 134
            if (twig_test_empty($this->getContext($context, "_breadcrumb"))) {
                // line 135
                echo "                                ";
                if (array_key_exists("action", $context)) {
                    // line 136
                    echo "                                    ";
                    $context['_parent'] = (array) $context;
                    $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getContext($context, "admin"), "breadcrumbs", array(0 => $this->getContext($context, "action")), "method"));
                    $context['loop'] = array(
                      'parent' => $context['_parent'],
                      'index0' => 0,
                      'index'  => 1,
                      'first'  => true,
                    );
                    if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof Countable)) {
                        $length = count($context['_seq']);
                        $context['loop']['revindex0'] = $length - 1;
                        $context['loop']['revindex'] = $length;
                        $context['loop']['length'] = $length;
                        $context['loop']['last'] = 1 === $length;
                    }
                    foreach ($context['_seq'] as $context["_key"] => $context["menu"]) {
                        // line 137
                        echo "                                        ";
                        if ((!$this->getAttribute($this->getContext($context, "loop"), "last"))) {
                            // line 138
                            echo "                                            <li>
                                                ";
                            // line 139
                            if ((!twig_test_empty($this->getAttribute($this->getContext($context, "menu"), "uri")))) {
                                // line 140
                                echo "                                                    <a href=\"";
                                echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "menu"), "uri"), "html", null, true);
                                echo "\">";
                                echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "menu"), "label"), "html", null, true);
                                echo "</a>
                                                ";
                            } else {
                                // line 142
                                echo "                                                    ";
                                echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "menu"), "label"), "html", null, true);
                                echo "
                                                ";
                            }
                            // line 144
                            echo "                                                <span class=\"divider\">/</span>
                                            </li>
                                        ";
                        } else {
                            // line 147
                            echo "                                            <li class=\"active\">";
                            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "menu"), "label"), "html", null, true);
                            echo "</li>
                                        ";
                        }
                        // line 149
                        echo "                                    ";
                        ++$context['loop']['index0'];
                        ++$context['loop']['index'];
                        $context['loop']['first'] = false;
                        if (isset($context['loop']['length'])) {
                            --$context['loop']['revindex0'];
                            --$context['loop']['revindex'];
                            $context['loop']['last'] = 0 === $context['loop']['revindex0'];
                        }
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['_key'], $context['menu'], $context['_parent'], $context['loop']);
                    $context = array_merge($_parent, array_intersect_key($context, $_parent));
                    // line 150
                    echo "                                ";
                }
                // line 151
                echo "                            ";
            } else {
                // line 152
                echo "                                ";
                echo $this->getContext($context, "_breadcrumb");
                echo "
                            ";
            }
            // line 154
            echo "                        </ul>
                    ";
        }
        // line 156
        echo "                </div>

                <div class=\"span4 offset2\">
                    ";
        // line 159
        $this->displayBlock('actions', $context, $blocks);
        // line 160
        echo "                </div>
            </div>

            <div class=\"row-fluid\">
                ";
        // line 164
        if ((!twig_test_empty($this->getContext($context, "_side_menu")))) {
            // line 165
            echo "                    <div class=\"sidebar span2\">
                        <div class=\"well sonata-ba-side-menu\" style=\"padding: 8px 0;\">";
            // line 166
            echo $this->getContext($context, "_side_menu");
            echo "</div>
                    </div>
                ";
        }
        // line 169
        echo "
                <div class=\"content ";
        // line 170
        echo (((!twig_test_empty($this->getContext($context, "_side_menu")))) ? (" span10") : ("span12"));
        echo "\">
                    ";
        // line 171
        $this->displayBlock('sonata_admin_content', $context, $blocks);
        // line 201
        echo "                </div>
            </div>

            ";
        // line 204
        $this->displayBlock('footer', $context, $blocks);
        // line 211
        echo "        </div>
    </body>
</html>
";
    }

    // line 27
    public function block_stylesheets($context, array $blocks = array())
    {
        // line 28
        echo "            <!-- jQuery code -->
            <link rel=\"stylesheet\" href=\"";
        // line 29
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/sonatajquery/themes/flick/jquery-ui-1.8.16.custom.css"), "html", null, true);
        echo "\" type=\"text/css\" media=\"all\" />

            <link rel=\"stylesheet\" href=\"";
        // line 31
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/sonataadmin/bootstrap/css/bootstrap.min.css"), "html", null, true);
        echo "\" type=\"text/css\" media=\"all\"  />
            <link rel=\"stylesheet\" href=\"";
        // line 32
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/sonataadmin/bootstrap/css/bootstrap-responsive.min.css"), "html", null, true);
        echo "\" type=\"text/css\" media=\"all\" />

            <!-- base application asset -->
            <link rel=\"stylesheet\" href=\"";
        // line 35
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/sonataadmin/css/layout.css"), "html", null, true);
        echo "\" type=\"text/css\" media=\"all\" />
            <link rel=\"stylesheet\" href=\"";
        // line 36
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/sonataadmin/css/colors.css"), "html", null, true);
        echo "\" type=\"text/css\" media=\"all\" />
        ";
    }

    // line 39
    public function block_javascripts($context, array $blocks = array())
    {
        // line 40
        echo "            <script src=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/sonatajquery/jquery-1.8.3.js"), "html", null, true);
        echo "\" type=\"text/javascript\"></script>
            <script src=\"";
        // line 41
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/sonatajquery/jquery-ui-1.8.23.js"), "html", null, true);
        echo "\" type=\"text/javascript\"></script>
            <script src=\"";
        // line 42
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/sonatajquery/jquery-ui-i18n.js"), "html", null, true);
        echo "\" type=\"text/javascript\"></script>

            <script src=\"";
        // line 44
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/sonataadmin/bootstrap/js/bootstrap.min.js"), "html", null, true);
        echo "\" type=\"text/javascript\"></script>

            <script src=\"";
        // line 46
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/sonataadmin/qtip/jquery.qtip-1.0.0-rc3.min.js"), "html", null, true);
        echo "\" type=\"text/javascript\"></script>
            <script src=\"";
        // line 47
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/sonataadmin/jquery/jquery.form.js"), "html", null, true);
        echo "\" type=\"text/javascript\"></script>
            <script src=\"";
        // line 48
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/sonataadmin/base.js"), "html", null, true);
        echo "\" type=\"text/javascript\"></script>
        ";
    }

    // line 84
    public function block_logo($context, array $blocks = array())
    {
        // line 85
        echo "                            <a href=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getUrl("sonata_admin_dashboard"), "html", null, true);
        echo "\" class=\"brand\">
                                <img src=\"";
        // line 86
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl($this->getAttribute($this->getContext($context, "admin_pool"), "titlelogo")), "html", null, true);
        echo "\"  alt=\"";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "admin_pool"), "title"), "html", null, true);
        echo "\" />
                                ";
        // line 87
        echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "admin_pool"), "title"), "html", null, true);
        echo "
                            </a>
                        ";
    }

    // line 93
    public function block_top_bar_before_nav($context, array $blocks = array())
    {
        echo " ";
    }

    // line 94
    public function block_sonata_top_bar_nav($context, array $blocks = array())
    {
        // line 95
        echo "                                    ";
        if (($this->getAttribute($this->getAttribute($this->getContext($context, "app"), "security"), "token") && $this->env->getExtension('security')->isGranted("ROLE_SONATA_ADMIN"))) {
            // line 96
            echo "                                        ";
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getContext($context, "admin_pool"), "dashboardgroups"));
            foreach ($context['_seq'] as $context["_key"] => $context["group"]) {
                // line 97
                echo "                                            <li class=\"dropdown\">
                                                <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\">";
                // line 98
                echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans($this->getAttribute($this->getContext($context, "group"), "label"), array(), $this->getAttribute($this->getContext($context, "group"), "label_catalogue")), "html", null, true);
                echo " <span class=\"caret\"></span></a>
                                                <ul class=\"dropdown-menu\">
                                                    ";
                // line 100
                $context['_parent'] = (array) $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getContext($context, "group"), "items"));
                foreach ($context['_seq'] as $context["_key"] => $context["admin"]) {
                    // line 101
                    echo "                                                        ";
                    if (($this->getAttribute($this->getContext($context, "admin"), "hasroute", array(0 => "list"), "method") && $this->getAttribute($this->getContext($context, "admin"), "isGranted", array(0 => "LIST"), "method"))) {
                        // line 102
                        echo "                                                            <li><a href=\"";
                        echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "admin"), "generateUrl", array(0 => "list"), "method"), "html", null, true);
                        echo "\">";
                        echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans($this->getAttribute($this->getContext($context, "admin"), "label"), array(), $this->getAttribute($this->getContext($context, "admin"), "translationdomain")), "html", null, true);
                        echo "</a></li>
                                                        ";
                    }
                    // line 104
                    echo "                                                    ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['admin'], $context['_parent'], $context['loop']);
                $context = array_merge($_parent, array_intersect_key($context, $_parent));
                // line 105
                echo "                                                </ul>
                                            </li>
                                        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['group'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 108
            echo "                                    ";
        }
        // line 109
        echo "                                ";
    }

    // line 110
    public function block_top_bar_after_nav($context, array $blocks = array())
    {
        echo " ";
    }

    // line 119
    public function block_notice($context, array $blocks = array())
    {
        // line 120
        echo "                ";
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable(array(0 => "success", 1 => "error", 2 => "info", 3 => "warning"));
        foreach ($context['_seq'] as $context["_key"] => $context["notice_level"]) {
            // line 121
            echo "                    ";
            $context["session_var"] = ("sonata_flash_" . $this->getContext($context, "notice_level"));
            // line 122
            echo "                    ";
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute($this->getAttribute($this->getContext($context, "app"), "session"), "flashbag"), "get", array(0 => $this->getContext($context, "session_var")), "method"));
            foreach ($context['_seq'] as $context["_key"] => $context["flash"]) {
                // line 123
                echo "                        <div class=\"alert ";
                echo twig_escape_filter($this->env, ("alert-" . $this->getContext($context, "notice_level")), "html", null, true);
                echo "\">
                            ";
                // line 124
                echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans($this->getContext($context, "flash"), array(), "SonataAdminBundle"), "html", null, true);
                echo "
                        </div>
                    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['flash'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 127
            echo "                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['notice_level'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 128
        echo "            ";
    }

    // line 159
    public function block_actions($context, array $blocks = array())
    {
    }

    // line 171
    public function block_sonata_admin_content($context, array $blocks = array())
    {
        // line 172
        echo "
                        ";
        // line 173
        if ((!twig_test_empty($this->getContext($context, "_preview")))) {
            // line 174
            echo "                            <div class=\"sonata-ba-preview\">";
            echo $this->getContext($context, "_preview");
            echo "</div>
                        ";
        }
        // line 176
        echo "
                        ";
        // line 177
        if ((!twig_test_empty($this->getContext($context, "_content")))) {
            // line 178
            echo "                            <div class=\"sonata-ba-content\">";
            echo $this->getContext($context, "_content");
            echo "</div>
                        ";
        }
        // line 180
        echo "
                        ";
        // line 181
        if ((!twig_test_empty($this->getContext($context, "_show")))) {
            // line 182
            echo "                            <div class=\"sonata-ba-show\">";
            echo $this->getContext($context, "_show");
            echo "</div>
                        ";
        }
        // line 184
        echo "
                        ";
        // line 185
        if ((!twig_test_empty($this->getContext($context, "_form")))) {
            // line 186
            echo "                            <div class=\"sonata-ba-form\">";
            echo $this->getContext($context, "_form");
            echo "</div>
                        ";
        }
        // line 188
        echo "
                        ";
        // line 189
        if (((!twig_test_empty($this->getContext($context, "_list_table"))) || (!twig_test_empty($this->getContext($context, "_list_filters"))))) {
            // line 190
            echo "                            <div class=\"row-fluid\">
                                <div class=\"sonata-ba-list span10\">
                                    ";
            // line 192
            echo $this->getContext($context, "_list_table");
            echo "
                                </div>
                                <div class=\"sonata-ba-filter span2\">
                                    ";
            // line 195
            echo $this->getContext($context, "_list_filters");
            echo "
                                </div>
                            </div>
                        ";
        }
        // line 199
        echo "
                    ";
    }

    // line 204
    public function block_footer($context, array $blocks = array())
    {
        // line 205
        echo "                <div class=\"row-fluid\">
                    <div class=\"span2 offset10 pull-right\">
                        <span class=\"label\"><a href=\"http://sonata-project.org\" rel=\"noreferrer\" style=\"text-decoration: none; color: black\">Sonata Project</a></span>
                    </div>
                </div>
            ";
    }

    public function getTemplateName()
    {
        return "SonataAdminBundle::standard_layout.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  613 => 205,  610 => 204,  605 => 199,  598 => 195,  592 => 192,  588 => 190,  586 => 189,  583 => 188,  577 => 186,  575 => 185,  572 => 184,  566 => 182,  564 => 181,  561 => 180,  555 => 178,  553 => 177,  550 => 176,  544 => 174,  542 => 173,  539 => 172,  536 => 171,  531 => 159,  527 => 128,  521 => 127,  512 => 124,  507 => 123,  502 => 122,  499 => 121,  494 => 120,  491 => 119,  485 => 110,  481 => 109,  478 => 108,  470 => 105,  464 => 104,  456 => 102,  453 => 101,  449 => 100,  444 => 98,  441 => 97,  436 => 96,  433 => 95,  430 => 94,  424 => 93,  417 => 87,  411 => 86,  406 => 85,  403 => 84,  397 => 48,  393 => 47,  389 => 46,  384 => 44,  379 => 42,  375 => 41,  370 => 40,  367 => 39,  361 => 36,  357 => 35,  351 => 32,  347 => 31,  342 => 29,  339 => 28,  336 => 27,  329 => 211,  327 => 204,  322 => 201,  320 => 171,  316 => 170,  313 => 169,  307 => 166,  304 => 165,  302 => 164,  296 => 160,  294 => 159,  289 => 156,  285 => 154,  279 => 152,  276 => 151,  273 => 150,  259 => 149,  253 => 147,  248 => 144,  242 => 142,  234 => 140,  232 => 139,  229 => 138,  226 => 137,  208 => 136,  205 => 135,  203 => 134,  200 => 133,  198 => 132,  193 => 129,  191 => 119,  184 => 114,  179 => 111,  176 => 110,  173 => 94,  171 => 93,  166 => 90,  164 => 84,  157 => 82,  155 => 81,  143 => 71,  137 => 69,  133 => 67,  130 => 66,  127 => 65,  110 => 63,  106 => 61,  103 => 60,  86 => 59,  83 => 58,  80 => 57,  74 => 55,  72 => 54,  67 => 52,  61 => 39,  58 => 38,  56 => 27,  47 => 20,  43 => 18,  41 => 17,  35 => 14,  33 => 13,  31 => 12,  84 => 35,  76 => 30,  63 => 50,  55 => 15,  49 => 12,  45 => 19,  39 => 16,  37 => 15,  32 => 5,  29 => 11,  26 => 3,);
    }
}
