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
        // line 51
        echo "
        <title>
            ";
        // line 53
        echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("Admin", array(), "SonataAdminBundle"), "html", null, true);
        echo "

            ";
        // line 55
        if ((!twig_test_empty($this->getContext($context, "_title")))) {
            // line 56
            echo "                ";
            echo $this->getContext($context, "_title");
            echo "
            ";
        } else {
            // line 58
            echo "                ";
            if (array_key_exists("action", $context)) {
                // line 59
                echo "                    -
                    ";
                // line 60
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
                foreach ($context['_seq'] as $context["label"] => $context["uri"]) {
                    // line 61
                    echo "                        ";
                    if ((!$this->getAttribute($this->getContext($context, "loop"), "first"))) {
                        // line 62
                        echo "                            &gt;
                        ";
                    }
                    // line 64
                    echo "                        ";
                    echo twig_escape_filter($this->env, $this->getContext($context, "label"), "html", null, true);
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
                unset($context['_seq'], $context['_iterated'], $context['label'], $context['uri'], $context['_parent'], $context['loop']);
                $context = array_merge($_parent, array_intersect_key($context, $_parent));
                // line 66
                echo "                ";
            }
            // line 67
            echo "            ";
        }
        // line 68
        echo "        </title>
    </head>
    <body class=\"sonata-bc ";
        // line 70
        if (twig_test_empty($this->getContext($context, "_side_menu"))) {
            echo "sonata-ba-no-side-menu";
        }
        echo "\">
        ";
        // line 72
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
        // line 82
        if (array_key_exists("admin_pool", $context)) {
            // line 83
            echo "                        <div class=\"navbar-text pull-right\">";
            $template = $this->env->resolveTemplate($this->getAttribute($this->getContext($context, "admin_pool"), "getTemplate", array(0 => "user_block"), "method"));
            $template->display($context);
            echo "</div>

                        ";
            // line 85
            $this->displayBlock('logo', $context, $blocks);
            // line 91
            echo "
                        <div class=\"nav-collapse\">
                            <ul class=\"nav\">
                                ";
            // line 94
            $this->displayBlock('top_bar_before_nav', $context, $blocks);
            // line 95
            echo "                                ";
            $this->displayBlock('sonata_top_bar_nav', $context, $blocks);
            // line 111
            echo "                                ";
            $this->displayBlock('top_bar_after_nav', $context, $blocks);
            // line 112
            echo "                            </ul>
                        </div>
                    ";
        }
        // line 115
        echo "                </div>
            </div>
        </div>

        <div class=\"container-fluid\">
            ";
        // line 120
        if (((!twig_test_empty($this->getContext($context, "_breadcrumb"))) || array_key_exists("action", $context))) {
            // line 121
            echo "                <ul class=\"breadcrumb\">
                    ";
            // line 122
            if (twig_test_empty($this->getContext($context, "_breadcrumb"))) {
                // line 123
                echo "                        ";
                if (array_key_exists("action", $context)) {
                    // line 124
                    echo "                            ";
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
                    foreach ($context['_seq'] as $context["label"] => $context["uri"]) {
                        // line 125
                        echo "                                ";
                        if ((!$this->getAttribute($this->getContext($context, "loop"), "last"))) {
                            // line 126
                            echo "                                    <li>
                                        ";
                            // line 127
                            if ((!twig_test_empty($this->getContext($context, "uri")))) {
                                // line 128
                                echo "                                            <a href=\"";
                                echo twig_escape_filter($this->env, $this->getContext($context, "uri"), "html", null, true);
                                echo "\">";
                                echo twig_escape_filter($this->env, $this->getContext($context, "label"), "html", null, true);
                                echo "</a>
                                        ";
                            } else {
                                // line 130
                                echo "                                            ";
                                echo twig_escape_filter($this->env, $this->getContext($context, "label"), "html", null, true);
                                echo "
                                        ";
                            }
                            // line 132
                            echo "                                        <span class=\"divider\">/</span>
                                    </li>
                                ";
                        } else {
                            // line 135
                            echo "                                    <li class=\"active\">";
                            echo twig_escape_filter($this->env, $this->getContext($context, "label"), "html", null, true);
                            echo "</li>
                                ";
                        }
                        // line 137
                        echo "                            ";
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
                    unset($context['_seq'], $context['_iterated'], $context['label'], $context['uri'], $context['_parent'], $context['loop']);
                    $context = array_merge($_parent, array_intersect_key($context, $_parent));
                    // line 138
                    echo "                        ";
                }
                // line 139
                echo "                    ";
            } else {
                // line 140
                echo "                        ";
                echo $this->getContext($context, "_breadcrumb");
                echo "
                    ";
            }
            // line 142
            echo "                </ul>
            ";
        }
        // line 144
        echo "
            ";
        // line 145
        $this->displayBlock('notice', $context, $blocks);
        // line 155
        echo "
            <div style=\"float: right\">
                ";
        // line 157
        $this->displayBlock('actions', $context, $blocks);
        // line 158
        echo "            </div>

            ";
        // line 160
        if (((!twig_test_empty($this->getContext($context, "_title"))) || array_key_exists("action", $context))) {
            // line 161
            echo "                <div class=\"page-header\">
                    <h1>
                        ";
            // line 163
            if ((!twig_test_empty($this->getContext($context, "_title")))) {
                // line 164
                echo "                            ";
                echo $this->getContext($context, "_title");
                echo "
                        ";
            } elseif (array_key_exists("action", $context)) {
                // line 166
                echo "                            ";
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
                foreach ($context['_seq'] as $context["label"] => $context["uri"]) {
                    // line 167
                    echo "                                ";
                    if ($this->getAttribute($this->getContext($context, "loop"), "last")) {
                        // line 168
                        echo "                                    ";
                        echo twig_escape_filter($this->env, $this->getContext($context, "label"), "html", null, true);
                        echo "
                                ";
                    }
                    // line 170
                    echo "                            ";
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
                unset($context['_seq'], $context['_iterated'], $context['label'], $context['uri'], $context['_parent'], $context['loop']);
                $context = array_merge($_parent, array_intersect_key($context, $_parent));
                // line 171
                echo "                        ";
            }
            // line 172
            echo "                    </h1>
                </div>
            ";
        }
        // line 175
        echo "
            <div class=\"row-fluid\">
                ";
        // line 177
        if ((!twig_test_empty($this->getContext($context, "_side_menu")))) {
            // line 178
            echo "                    <div class=\"sidebar span2\">
                        <div class=\"well sonata-ba-side-menu\" style=\"padding: 8px 0;\">";
            // line 179
            echo $this->getContext($context, "_side_menu");
            echo "</div>
                    </div>
                ";
        }
        // line 182
        echo "
                <div class=\"content ";
        // line 183
        echo (((!twig_test_empty($this->getContext($context, "_side_menu")))) ? (" span10") : ("span12"));
        echo "\">
                ";
        // line 184
        $this->displayBlock('sonata_admin_content', $context, $blocks);
        // line 212
        echo "                </div>


            </div>

            ";
        // line 217
        $this->displayBlock('footer', $context, $blocks);
        // line 222
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
            <script src=\"";
        // line 49
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/sonataadmin/js/ckeditor/ckeditor.js"), "html", null, true);
        echo "\" type=\"text/javascript\"></script>
        ";
    }

    // line 85
    public function block_logo($context, array $blocks = array())
    {
        // line 86
        echo "                            <a href=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getUrl("sonata_admin_dashboard"), "html", null, true);
        echo "\" class=\"brand\">
                                <img src=\"";
        // line 87
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl($this->getAttribute($this->getContext($context, "admin_pool"), "titlelogo")), "html", null, true);
        echo "\"  alt=\"";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "admin_pool"), "title"), "html", null, true);
        echo "\" />
                                ";
        // line 88
        echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "admin_pool"), "title"), "html", null, true);
        echo "
                            </a>
                        ";
    }

    // line 94
    public function block_top_bar_before_nav($context, array $blocks = array())
    {
        echo " ";
    }

    // line 95
    public function block_sonata_top_bar_nav($context, array $blocks = array())
    {
        // line 96
        echo "                                    ";
        if (($this->getAttribute($this->getAttribute($this->getContext($context, "app"), "security"), "token") && $this->env->getExtension('security')->isGranted("ROLE_SONATA_ADMIN"))) {
            // line 97
            echo "                                        ";
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getContext($context, "admin_pool"), "dashboardgroups"));
            foreach ($context['_seq'] as $context["_key"] => $context["group"]) {
                // line 98
                echo "                                            <li class=\"dropdown\">
                                                <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\">";
                // line 99
                echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans($this->getAttribute($this->getContext($context, "group"), "label"), array(), $this->getAttribute($this->getContext($context, "group"), "label_catalogue")), "html", null, true);
                echo " <span class=\"caret\"></span></a>
                                                <ul class=\"dropdown-menu\">
                                                    ";
                // line 101
                $context['_parent'] = (array) $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getContext($context, "group"), "items"));
                foreach ($context['_seq'] as $context["_key"] => $context["admin"]) {
                    // line 102
                    echo "                                                        ";
                    if (($this->getAttribute($this->getContext($context, "admin"), "hasroute", array(0 => "list"), "method") && $this->getAttribute($this->getContext($context, "admin"), "isGranted", array(0 => "LIST"), "method"))) {
                        // line 103
                        echo "                                                            <li><a href=\"";
                        echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "admin"), "generateUrl", array(0 => "list"), "method"), "html", null, true);
                        echo "\">";
                        echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans($this->getAttribute($this->getContext($context, "admin"), "label"), array(), $this->getAttribute($this->getContext($context, "admin"), "translationdomain")), "html", null, true);
                        echo "</a></li>
                                                        ";
                    }
                    // line 105
                    echo "                                                    ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['admin'], $context['_parent'], $context['loop']);
                $context = array_merge($_parent, array_intersect_key($context, $_parent));
                // line 106
                echo "                                                </ul>
                                            </li>
                                        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['group'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 109
            echo "                                    ";
        }
        // line 110
        echo "                                ";
    }

    // line 111
    public function block_top_bar_after_nav($context, array $blocks = array())
    {
        echo " ";
    }

    // line 145
    public function block_notice($context, array $blocks = array())
    {
        // line 146
        echo "                ";
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable(array(0 => "success", 1 => "error", 2 => "info", 3 => "warning"));
        foreach ($context['_seq'] as $context["_key"] => $context["notice_level"]) {
            // line 147
            echo "                    ";
            $context["session_var"] = ("sonata_flash_" . $this->getContext($context, "notice_level"));
            // line 148
            echo "                    ";
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute($this->getAttribute($this->getContext($context, "app"), "session"), "flashbag"), "get", array(0 => $this->getContext($context, "session_var")), "method"));
            foreach ($context['_seq'] as $context["_key"] => $context["flash"]) {
                // line 149
                echo "                        <div class=\"alert ";
                echo twig_escape_filter($this->env, ("alert-" . $this->getContext($context, "notice_level")), "html", null, true);
                echo "\">
                            ";
                // line 150
                echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans($this->getContext($context, "flash"), array(), "SonataAdminBundle"), "html", null, true);
                echo "
                        </div>
                    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['flash'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 153
            echo "                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['notice_level'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 154
        echo "            ";
    }

    // line 157
    public function block_actions($context, array $blocks = array())
    {
    }

    // line 184
    public function block_sonata_admin_content($context, array $blocks = array())
    {
        // line 185
        echo "
                    ";
        // line 186
        if ((!twig_test_empty($this->getContext($context, "_preview")))) {
            // line 187
            echo "                        <div class=\"sonata-ba-preview\">";
            echo $this->getContext($context, "_preview");
            echo "</div>
                    ";
        }
        // line 189
        echo "
                    ";
        // line 190
        if ((!twig_test_empty($this->getContext($context, "_content")))) {
            // line 191
            echo "                        <div class=\"sonata-ba-content\">";
            echo $this->getContext($context, "_content");
            echo "</div>
                    ";
        }
        // line 193
        echo "
                    ";
        // line 194
        if ((!twig_test_empty($this->getContext($context, "_show")))) {
            // line 195
            echo "                        <div class=\"sonata-ba-show\">";
            echo $this->getContext($context, "_show");
            echo "</div>
                    ";
        }
        // line 197
        echo "
                    ";
        // line 198
        if ((!twig_test_empty($this->getContext($context, "_form")))) {
            // line 199
            echo "                        <div class=\"sonata-ba-form\">";
            echo $this->getContext($context, "_form");
            echo "</div>
                    ";
        }
        // line 201
        echo "
                    ";
        // line 202
        if (((!twig_test_empty($this->getContext($context, "_list_table"))) || (!twig_test_empty($this->getContext($context, "_list_filters"))))) {
            // line 203
            echo "                        <div class=\"sonata-ba-filter\">
                            ";
            // line 204
            echo $this->getContext($context, "_list_filters");
            echo "
                        </div>
                        <div class=\"sonata-ba-list\">
                            ";
            // line 207
            echo $this->getContext($context, "_list_table");
            echo "
                        </div>
                    ";
        }
        // line 210
        echo "
                ";
    }

    // line 217
    public function block_footer($context, array $blocks = array())
    {
        // line 218
        echo "                <div class=\"pull-right clearfix\">
                    <span class=\"label\"><a href=\"http://sonata-project.org\" rel=\"noreferrer\" style=\"text-decoration: none; color: black\">Sonata Project</a></span>
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
        return array (  679 => 218,  676 => 217,  671 => 210,  665 => 207,  659 => 204,  656 => 203,  654 => 202,  651 => 201,  645 => 199,  643 => 198,  640 => 197,  634 => 195,  632 => 194,  629 => 193,  623 => 191,  621 => 190,  618 => 189,  612 => 187,  610 => 186,  607 => 185,  604 => 184,  599 => 157,  595 => 154,  589 => 153,  580 => 150,  575 => 149,  570 => 148,  567 => 147,  562 => 146,  559 => 145,  553 => 111,  549 => 110,  546 => 109,  538 => 106,  532 => 105,  524 => 103,  521 => 102,  517 => 101,  512 => 99,  509 => 98,  504 => 97,  501 => 96,  498 => 95,  492 => 94,  485 => 88,  479 => 87,  474 => 86,  471 => 85,  465 => 49,  461 => 48,  457 => 47,  453 => 46,  448 => 44,  443 => 42,  439 => 41,  434 => 40,  431 => 39,  425 => 36,  421 => 35,  415 => 32,  411 => 31,  406 => 29,  403 => 28,  400 => 27,  393 => 222,  391 => 217,  384 => 212,  382 => 184,  378 => 183,  375 => 182,  369 => 179,  366 => 178,  364 => 177,  360 => 175,  355 => 172,  352 => 171,  338 => 170,  332 => 168,  329 => 167,  311 => 166,  305 => 164,  303 => 163,  299 => 161,  297 => 160,  293 => 158,  291 => 157,  287 => 155,  285 => 145,  282 => 144,  278 => 142,  272 => 140,  269 => 139,  266 => 138,  252 => 137,  246 => 135,  241 => 132,  235 => 130,  227 => 128,  225 => 127,  222 => 126,  219 => 125,  201 => 124,  198 => 123,  196 => 122,  193 => 121,  191 => 120,  184 => 115,  179 => 112,  176 => 111,  173 => 95,  171 => 94,  166 => 91,  164 => 85,  157 => 83,  155 => 82,  143 => 72,  137 => 70,  133 => 68,  130 => 67,  127 => 66,  110 => 64,  106 => 62,  103 => 61,  86 => 60,  83 => 59,  80 => 58,  74 => 56,  72 => 55,  67 => 53,  61 => 39,  58 => 38,  56 => 27,  47 => 20,  43 => 18,  41 => 17,  35 => 14,  33 => 13,  31 => 12,  84 => 35,  76 => 30,  63 => 51,  55 => 15,  49 => 12,  45 => 19,  39 => 16,  37 => 15,  32 => 5,  29 => 11,  26 => 3,);
    }
}
