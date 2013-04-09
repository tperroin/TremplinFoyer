<?php

/* SonataAdminBundle:CRUD:base_edit_form.html.twig */
class __TwigTemplate_c0f7c0a5d1ddfbf925a31f87e3e625a5 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'form' => array($this, 'block_form'),
            'sonata_pre_fieldsets' => array($this, 'block_sonata_pre_fieldsets'),
            'sonata_post_fieldsets' => array($this, 'block_sonata_post_fieldsets'),
            'formactions' => array($this, 'block_formactions'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        $this->displayBlock('form', $context, $blocks);
    }

    public function block_form($context, array $blocks = array())
    {
        // line 2
        echo "    ";
        $context["url"] = (($this->getAttribute($this->getContext($context, "admin"), "id", array(0 => $this->getContext($context, "object")), "method")) ? ("edit") : ("create"));
        // line 3
        echo "
    ";
        // line 4
        if ((!$this->getAttribute($this->getContext($context, "admin"), "hasRoute", array(0 => $this->getContext($context, "url")), "method"))) {
            // line 5
            echo "        <div>
            ";
            // line 6
            echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("form_not_available", array(), "SonataAdminBundle"), "html", null, true);
            echo "
        </div>
    ";
        } else {
            // line 9
            echo "        <form class=\"form-horizontal\" action=\"";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "admin"), "generateUrl", array(0 => $this->getContext($context, "url"), 1 => array("id" => $this->getAttribute($this->getContext($context, "admin"), "id", array(0 => $this->getContext($context, "object")), "method"), "uniqid" => $this->getAttribute($this->getContext($context, "admin"), "uniqid"), "subclass" => $this->getAttribute($this->getAttribute($this->getContext($context, "app"), "request"), "get", array(0 => "subclass"), "method"))), "method"), "html", null, true);
            echo "\" ";
            echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getContext($context, "form"), 'enctype');
            echo " method=\"POST\">
            ";
            // line 10
            if ((twig_length_filter($this->env, $this->getAttribute($this->getAttribute($this->getContext($context, "form"), "vars"), "errors")) > 0)) {
                // line 11
                echo "                <div class=\"sonata-ba-form-error\">
                    ";
                // line 12
                echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getContext($context, "form"), 'errors');
                echo "
                </div>
            ";
            }
            // line 15
            echo "
            ";
            // line 16
            $this->displayBlock('sonata_pre_fieldsets', $context, $blocks);
            // line 29
            echo "
                <div class=\"tab-content\">
                    ";
            // line 31
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getContext($context, "admin"), "formgroups"));
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
            foreach ($context['_seq'] as $context["name"] => $context["form_group"]) {
                // line 32
                echo "                        <div class=\"tab-pane ";
                if ($this->getAttribute($this->getContext($context, "loop"), "first")) {
                    echo " active";
                }
                echo "\" id=\"";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "admin"), "uniqid"), "html", null, true);
                echo "_";
                echo twig_escape_filter($this->env, $this->env->getExtension('sonata_admin')->slugify($this->getContext($context, "name")), "html", null, true);
                echo "\">
                            <fieldset>
                                <div class=\"sonata-ba-collapsed-fields\">
                                    ";
                // line 35
                if (($this->getAttribute($this->getContext($context, "form_group"), "description") != false)) {
                    // line 36
                    echo "                                        <p>";
                    echo $this->getAttribute($this->getContext($context, "form_group"), "description");
                    echo "</p>
                                    ";
                }
                // line 38
                echo "
                                    ";
                // line 39
                $context['_parent'] = (array) $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getContext($context, "form_group"), "fields"));
                foreach ($context['_seq'] as $context["_key"] => $context["field_name"]) {
                    // line 40
                    echo "                                        ";
                    if ($this->getAttribute($this->getAttribute($this->getContext($context, "admin", true), "formfielddescriptions", array(), "any", false, true), $this->getContext($context, "field_name"), array(), "array", true, true)) {
                        // line 41
                        echo "                                            ";
                        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute($this->getContext($context, "form"), $this->getContext($context, "field_name"), array(), "array"), 'row');
                        echo "
                                        ";
                    }
                    // line 43
                    echo "                                    ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['field_name'], $context['_parent'], $context['loop']);
                $context = array_merge($_parent, array_intersect_key($context, $_parent));
                // line 44
                echo "                                </div>
                            </fieldset>
                        </div>
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
            unset($context['_seq'], $context['_iterated'], $context['name'], $context['form_group'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 48
            echo "                </div>

            ";
            // line 50
            $this->displayBlock('sonata_post_fieldsets', $context, $blocks);
            // line 53
            echo "
            ";
            // line 54
            echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getContext($context, "form"), 'rest');
            echo "

            ";
            // line 56
            $this->displayBlock('formactions', $context, $blocks);
            // line 87
            echo "        </form>
    ";
        }
        // line 89
        echo "
";
    }

    // line 16
    public function block_sonata_pre_fieldsets($context, array $blocks = array())
    {
        // line 17
        echo "                <div class=\"tabbable\">
                    <ul class=\"nav nav-tabs\">
                        ";
        // line 19
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getContext($context, "admin"), "formgroups"));
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
        foreach ($context['_seq'] as $context["name"] => $context["form_group"]) {
            // line 20
            echo "                            <li class=\"";
            if ($this->getAttribute($this->getContext($context, "loop"), "first")) {
                echo "active";
            }
            echo "\">
                                <a href=\"#";
            // line 21
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "admin"), "uniqid"), "html", null, true);
            echo "_";
            echo twig_escape_filter($this->env, $this->env->getExtension('sonata_admin')->slugify($this->getContext($context, "name")), "html", null, true);
            echo "\" data-toggle=\"tab\">
                                    <i class=\"icon-exclamation-sign has-errors hide\"></i>
                                    ";
            // line 23
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "admin"), "trans", array(0 => $this->getContext($context, "name")), "method"), "html", null, true);
            echo "
                                </a>
                            </li>
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
        unset($context['_seq'], $context['_iterated'], $context['name'], $context['form_group'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 27
        echo "                    </ul>
            ";
    }

    // line 50
    public function block_sonata_post_fieldsets($context, array $blocks = array())
    {
        // line 51
        echo "                </div>
            ";
    }

    // line 56
    public function block_formactions($context, array $blocks = array())
    {
        // line 57
        echo "                <div class=\"well form-actions\">
                    ";
        // line 58
        if ($this->getAttribute($this->getAttribute($this->getContext($context, "app"), "request"), "isxmlhttprequest")) {
            // line 59
            echo "                        ";
            if ($this->getAttribute($this->getContext($context, "admin"), "id", array(0 => $this->getContext($context, "object")), "method")) {
                // line 60
                echo "                            <input type=\"submit\" class=\"btn btn-primary\" name=\"btn_update\" value=\"";
                echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("btn_update", array(), "SonataAdminBundle"), "html", null, true);
                echo "\"/>
                        ";
            } else {
                // line 62
                echo "                            <input type=\"submit\" class=\"btn\" name=\"btn_create\" value=\"";
                echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("btn_create", array(), "SonataAdminBundle"), "html", null, true);
                echo "\"/>
                        ";
            }
            // line 64
            echo "                    ";
        } else {
            // line 65
            echo "                        ";
            if ($this->getAttribute($this->getContext($context, "admin"), "supportsPreviewMode")) {
                // line 66
                echo "                            <input class=\"btn btn-info persist-preview\" name=\"btn_preview\" type=\"submit\" value=\"";
                echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("btn_preview", array(), "SonataAdminBundle"), "html", null, true);
                echo "\"/>
                        ";
            }
            // line 68
            echo "                        ";
            if ($this->getAttribute($this->getContext($context, "admin"), "id", array(0 => $this->getContext($context, "object")), "method")) {
                // line 69
                echo "                            <input type=\"submit\" class=\"btn btn-primary\" name=\"btn_update_and_edit\" value=\"";
                echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("btn_update_and_edit_again", array(), "SonataAdminBundle"), "html", null, true);
                echo "\"/>

                            ";
                // line 71
                if ($this->getAttribute($this->getContext($context, "admin"), "hasroute", array(0 => "list"), "method")) {
                    // line 72
                    echo "                                <input type=\"submit\" class=\"btn\" name=\"btn_update_and_list\" value=\"";
                    echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("btn_update_and_return_to_list", array(), "SonataAdminBundle"), "html", null, true);
                    echo "\"/>
                            ";
                }
                // line 74
                echo "
                            ";
                // line 75
                if (($this->getAttribute($this->getContext($context, "admin"), "hasroute", array(0 => "delete"), "method") && $this->getAttribute($this->getContext($context, "admin"), "isGranted", array(0 => "DELETE", 1 => $this->getContext($context, "object")), "method"))) {
                    // line 76
                    echo "                                ";
                    echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("delete_or", array(), "SonataAdminBundle"), "html", null, true);
                    echo "
                                <a class=\"btn btn-danger\" href=\"";
                    // line 77
                    echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "admin"), "generateObjectUrl", array(0 => "delete", 1 => $this->getContext($context, "object")), "method"), "html", null, true);
                    echo "\">";
                    echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("link_delete", array(), "SonataAdminBundle"), "html", null, true);
                    echo "</a>
                            ";
                }
                // line 79
                echo "                        ";
            } else {
                // line 80
                echo "                            <input class=\"btn btn-primary\" type=\"submit\" name=\"btn_create_and_edit\" value=\"";
                echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("btn_create_and_edit_again", array(), "SonataAdminBundle"), "html", null, true);
                echo "\"/>
                            <input type=\"submit\" class=\"btn\" name=\"btn_create_and_list\" value=\"";
                // line 81
                echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("btn_create_and_return_to_list", array(), "SonataAdminBundle"), "html", null, true);
                echo "\"/>
                            <input class=\"btn\" type=\"submit\" name=\"btn_create_and_create\" value=\"";
                // line 82
                echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("btn_create_and_create_a_new_one", array(), "SonataAdminBundle"), "html", null, true);
                echo "\"/>
                        ";
            }
            // line 84
            echo "                    ";
        }
        // line 85
        echo "                </div>
            ";
    }

    public function getTemplateName()
    {
        return "SonataAdminBundle:CRUD:base_edit_form.html.twig";
    }

    public function getDebugInfo()
    {
        return array (  333 => 85,  330 => 84,  325 => 82,  299 => 75,  290 => 72,  288 => 71,  270 => 65,  267 => 64,  261 => 62,  244 => 56,  239 => 51,  231 => 27,  213 => 23,  170 => 89,  159 => 54,  150 => 48,  124 => 47,  113 => 40,  12 => 45,  631 => 203,  625 => 201,  623 => 200,  617 => 196,  608 => 193,  604 => 192,  595 => 190,  591 => 189,  579 => 185,  571 => 183,  568 => 182,  565 => 181,  549 => 98,  545 => 97,  534 => 92,  529 => 91,  526 => 90,  514 => 79,  511 => 78,  505 => 90,  501 => 88,  496 => 77,  493 => 76,  489 => 171,  459 => 159,  442 => 155,  434 => 153,  431 => 152,  415 => 147,  410 => 145,  394 => 139,  332 => 117,  301 => 76,  284 => 110,  265 => 103,  262 => 76,  257 => 74,  249 => 71,  185 => 55,  174 => 51,  613 => 205,  610 => 204,  605 => 199,  598 => 191,  592 => 192,  588 => 190,  586 => 187,  583 => 188,  577 => 186,  564 => 181,  561 => 180,  553 => 177,  550 => 176,  544 => 174,  542 => 173,  539 => 172,  536 => 171,  531 => 159,  527 => 128,  507 => 102,  502 => 122,  499 => 78,  494 => 120,  491 => 119,  485 => 110,  481 => 109,  478 => 108,  470 => 105,  464 => 104,  449 => 100,  441 => 97,  436 => 96,  433 => 95,  424 => 93,  417 => 87,  411 => 86,  406 => 85,  403 => 84,  397 => 48,  389 => 137,  370 => 128,  367 => 39,  361 => 127,  357 => 126,  351 => 32,  347 => 124,  342 => 29,  339 => 28,  336 => 27,  327 => 204,  320 => 171,  316 => 80,  313 => 79,  307 => 166,  304 => 165,  296 => 74,  253 => 147,  248 => 144,  242 => 142,  234 => 140,  232 => 139,  226 => 65,  205 => 135,  198 => 132,  179 => 111,  155 => 81,  127 => 43,  335 => 94,  326 => 90,  306 => 77,  303 => 86,  279 => 68,  276 => 151,  273 => 66,  271 => 79,  259 => 75,  255 => 60,  245 => 66,  240 => 64,  218 => 57,  211 => 53,  206 => 21,  190 => 50,  181 => 47,  172 => 44,  167 => 42,  134 => 42,  65 => 5,  63 => 22,  686 => 206,  680 => 203,  677 => 202,  675 => 201,  669 => 198,  659 => 197,  654 => 195,  642 => 193,  639 => 192,  636 => 205,  627 => 185,  624 => 184,  607 => 182,  590 => 181,  585 => 179,  581 => 178,  578 => 177,  575 => 185,  572 => 184,  566 => 182,  562 => 169,  560 => 100,  555 => 178,  538 => 93,  521 => 127,  517 => 163,  512 => 124,  509 => 161,  506 => 160,  503 => 159,  500 => 158,  498 => 157,  495 => 156,  486 => 151,  482 => 166,  477 => 147,  475 => 146,  472 => 163,  468 => 125,  462 => 123,  456 => 158,  450 => 157,  443 => 140,  432 => 136,  426 => 151,  421 => 131,  416 => 129,  405 => 127,  400 => 142,  391 => 138,  386 => 115,  380 => 112,  377 => 111,  375 => 41,  371 => 109,  366 => 107,  359 => 106,  356 => 105,  353 => 125,  343 => 122,  340 => 97,  331 => 92,  329 => 116,  324 => 113,  321 => 81,  318 => 90,  310 => 112,  302 => 164,  289 => 156,  286 => 85,  282 => 69,  277 => 108,  274 => 77,  272 => 76,  269 => 75,  263 => 71,  254 => 68,  250 => 58,  243 => 65,  238 => 64,  236 => 50,  233 => 62,  228 => 59,  223 => 64,  200 => 61,  197 => 60,  191 => 56,  184 => 114,  178 => 17,  175 => 16,  146 => 46,  118 => 40,  110 => 63,  104 => 30,  90 => 32,  81 => 172,  59 => 155,  23 => 1,  76 => 69,  58 => 12,  53 => 10,  20 => 11,  152 => 35,  148 => 63,  137 => 43,  102 => 36,  100 => 28,  97 => 27,  77 => 27,  34 => 16,  480 => 148,  474 => 164,  469 => 162,  461 => 160,  457 => 153,  453 => 101,  444 => 98,  440 => 148,  437 => 138,  435 => 137,  430 => 94,  427 => 143,  423 => 149,  413 => 146,  409 => 132,  407 => 131,  402 => 143,  398 => 129,  393 => 47,  387 => 122,  384 => 134,  381 => 120,  379 => 131,  374 => 116,  368 => 112,  365 => 111,  362 => 110,  360 => 109,  355 => 106,  341 => 121,  337 => 96,  322 => 201,  314 => 99,  312 => 89,  309 => 97,  305 => 95,  298 => 84,  294 => 159,  285 => 154,  283 => 84,  278 => 86,  268 => 104,  264 => 84,  258 => 81,  252 => 59,  247 => 57,  241 => 67,  235 => 74,  229 => 138,  224 => 71,  220 => 63,  214 => 69,  208 => 136,  169 => 50,  143 => 45,  140 => 44,  132 => 51,  128 => 40,  119 => 37,  107 => 24,  71 => 25,  38 => 15,  177 => 65,  165 => 64,  160 => 66,  135 => 47,  126 => 51,  114 => 39,  84 => 16,  70 => 20,  67 => 16,  61 => 26,  94 => 38,  89 => 176,  85 => 30,  75 => 21,  68 => 24,  56 => 24,  196 => 90,  183 => 70,  171 => 93,  166 => 87,  163 => 49,  158 => 67,  156 => 53,  151 => 48,  142 => 59,  138 => 57,  136 => 56,  121 => 41,  117 => 36,  105 => 36,  91 => 32,  62 => 156,  49 => 16,  87 => 17,  21 => 11,  31 => 23,  25 => 13,  26 => 14,  28 => 22,  24 => 13,  19 => 11,  93 => 41,  88 => 6,  78 => 21,  46 => 9,  44 => 74,  27 => 14,  79 => 70,  72 => 54,  69 => 29,  47 => 21,  40 => 6,  37 => 5,  22 => 11,  246 => 70,  157 => 82,  145 => 46,  139 => 31,  131 => 41,  123 => 51,  120 => 21,  115 => 49,  111 => 38,  108 => 38,  101 => 43,  98 => 21,  96 => 34,  83 => 58,  74 => 31,  66 => 23,  55 => 11,  52 => 24,  50 => 22,  43 => 14,  41 => 16,  35 => 4,  32 => 3,  29 => 2,  209 => 82,  203 => 62,  199 => 20,  193 => 129,  189 => 71,  187 => 49,  182 => 19,  176 => 110,  173 => 94,  168 => 66,  164 => 56,  162 => 62,  154 => 50,  149 => 47,  147 => 33,  144 => 32,  141 => 61,  133 => 44,  130 => 66,  125 => 39,  122 => 38,  116 => 36,  112 => 48,  109 => 18,  106 => 61,  103 => 35,  99 => 35,  95 => 20,  92 => 19,  86 => 175,  82 => 29,  80 => 25,  73 => 31,  64 => 15,  60 => 24,  57 => 18,  54 => 22,  51 => 16,  48 => 15,  45 => 14,  42 => 19,  39 => 25,  36 => 17,  33 => 16,  30 => 15,);
    }
}
