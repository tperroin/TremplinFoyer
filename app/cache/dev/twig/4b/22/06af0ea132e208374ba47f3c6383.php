<?php

/* SonataMediaBundle:MediaAdmin:list.html.twig */
class __TwigTemplate_4b2206af0ea132e208374ba47f3c6383 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("SonataAdminBundle:CRUD:base_list.html.twig");

        $this->blocks = array(
            'preview' => array($this, 'block_preview'),
            'list_filters' => array($this, 'block_list_filters'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "SonataAdminBundle:CRUD:base_list.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 14
    public function block_preview($context, array $blocks = array())
    {
        // line 15
        echo "
    <ul class=\"nav nav-pills\">
        <li><a><strong>";
        // line 17
        echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("label.select_context", array(), "SonataMediaBundle"), "html", null, true);
        echo "</strong></a></li>
        ";
        // line 18
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getContext($context, "media_pool"), "contexts"));
        foreach ($context['_seq'] as $context["name"] => $context["context"]) {
            // line 19
            echo "            ";
            if ((twig_length_filter($this->env, $this->getAttribute($this->getContext($context, "context"), "providers")) == 0)) {
                // line 20
                echo "                ";
                $context["urlParams"] = array("context" => $this->getContext($context, "name"));
                // line 21
                echo "            ";
            } else {
                // line 22
                echo "                ";
                $context["urlParams"] = array("context" => $this->getContext($context, "name"), "provider" => $this->getAttribute($this->getAttribute($this->getContext($context, "context"), "providers"), 0, array(), "array"));
                // line 23
                echo "            ";
            }
            // line 24
            echo "
            ";
            // line 25
            if (($this->getContext($context, "name") == $this->getAttribute($this->getContext($context, "persistent_parameters"), "context"))) {
                // line 26
                echo "                <li class=\"active\"><a href=\"";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "admin"), "generateUrl", array(0 => "list", 1 => $this->getContext($context, "urlParams")), "method"), "html", null, true);
                echo "\">";
                echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans($this->getContext($context, "name"), array(), "SonataMediaBundle"), "html", null, true);
                echo "</a></li>
            ";
            } else {
                // line 28
                echo "                <li><a href=\"";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "admin"), "generateUrl", array(0 => "list", 1 => $this->getContext($context, "urlParams")), "method"), "html", null, true);
                echo "\">";
                echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans($this->getContext($context, "name"), array(), "SonataMediaBundle"), "html", null, true);
                echo "</a></li>
            ";
            }
            // line 30
            echo "        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['name'], $context['context'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 31
        echo "
        ";
        // line 32
        $context["providers"] = $this->getAttribute($this->getContext($context, "media_pool"), "getProviderNamesByContext", array(0 => $this->getAttribute($this->getContext($context, "persistent_parameters"), "context")), "method");
        // line 33
        echo "
        ";
        // line 34
        if ((twig_length_filter($this->env, $this->getContext($context, "providers")) > 1)) {
            // line 35
            echo "            <li><a><strong>";
            echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("label.select_provider", array(), "SonataMediaBundle"), "html", null, true);
            echo "</strong></a></li>

            ";
            // line 37
            if ((!$this->getAttribute($this->getContext($context, "persistent_parameters"), "provider"))) {
                // line 38
                echo "                <li class=\"active\"><a href=\"";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "admin"), "generateUrl", array(0 => "list", 1 => array("context" => $this->getAttribute($this->getContext($context, "persistent_parameters"), "context"), "provider" => null)), "method"), "html", null, true);
                echo "\">";
                echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("link.all_providers", array(), "SonataMediaBundle"), "html", null, true);
                echo "</a></li>
            ";
            } else {
                // line 40
                echo "                <li><a href=\"";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "admin"), "generateUrl", array(0 => "list", 1 => array("context" => $this->getAttribute($this->getContext($context, "persistent_parameters"), "context"), "provider" => null)), "method"), "html", null, true);
                echo "\">";
                echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("link.all_providers", array(), "SonataMediaBundle"), "html", null, true);
                echo "</a></li>
            ";
            }
            // line 42
            echo "
            ";
            // line 43
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getContext($context, "providers"));
            foreach ($context['_seq'] as $context["_key"] => $context["provider_name"]) {
                // line 44
                echo "                ";
                if (($this->getAttribute($this->getContext($context, "persistent_parameters"), "provider") == $this->getContext($context, "provider_name"))) {
                    // line 45
                    echo "                    <li class=\"active\"><a href=\"";
                    echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "admin"), "generateUrl", array(0 => "list", 1 => array("context" => $this->getAttribute($this->getContext($context, "persistent_parameters"), "context"), "provider" => $this->getContext($context, "provider_name"))), "method"), "html", null, true);
                    echo "\">";
                    echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans($this->getContext($context, "provider_name"), array(), "SonataMediaBundle"), "html", null, true);
                    echo "</a></li>
                ";
                } else {
                    // line 47
                    echo "                    <li><a href=\"";
                    echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "admin"), "generateUrl", array(0 => "list", 1 => array("context" => $this->getAttribute($this->getContext($context, "persistent_parameters"), "context"), "provider" => $this->getContext($context, "provider_name"))), "method"), "html", null, true);
                    echo "\">";
                    echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans($this->getContext($context, "provider_name"), array(), "SonataMediaBundle"), "html", null, true);
                    echo "</a></li>
                ";
                }
                // line 49
                echo "            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['provider_name'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 50
            echo "        ";
        }
        // line 51
        echo "    </ul>

";
    }

    // line 56
    public function block_list_filters($context, array $blocks = array())
    {
        // line 57
        echo "    ";
        if ($this->getAttribute($this->getAttribute($this->getContext($context, "admin"), "datagrid"), "filters")) {
            // line 58
            echo "        <form class=\"sonata-filter-form form-stacked ";
            echo ((($this->getAttribute($this->getContext($context, "admin"), "isChild") && (1 == twig_length_filter($this->env, $this->getAttribute($this->getAttribute($this->getContext($context, "admin"), "datagrid"), "filters"))))) ? ("hide") : (""));
            echo "\" action=\"";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "admin"), "generateUrl", array(0 => "list"), "method"), "html", null, true);
            echo "\" method=\"GET\">

            <input type=\"hidden\" name=\"context\" value=\"";
            // line 60
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "persistent_parameters"), "context"), "html", null, true);
            echo "\" />
            <input type=\"hidden\" name=\"provider\" value=\"";
            // line 61
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "persistent_parameters"), "provider"), "html", null, true);
            echo "\" />

            <fieldset class=\"filter_legend\">
                <legend class=\"filter_legend ";
            // line 64
            echo (($this->getAttribute($this->getAttribute($this->getContext($context, "admin"), "datagrid"), "hasActiveFilters")) ? ("active") : ("inactive"));
            echo "\">";
            echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("label_filters", array(), "SonataAdminBundle"), "html", null, true);
            echo "</legend>

                <div class=\"filter_container ";
            // line 66
            echo (($this->getAttribute($this->getAttribute($this->getContext($context, "admin"), "datagrid"), "hasActiveFilters")) ? ("active") : ("inactive"));
            echo "\">
                    <table class=\"table table-bordered\">
                        ";
            // line 68
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute($this->getContext($context, "admin"), "datagrid"), "filters"));
            foreach ($context['_seq'] as $context["_key"] => $context["filter"]) {
                // line 69
                echo "                            <tr id=\"filter_";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "filter"), "name"), "html", null, true);
                echo "_row\" class=\"filter ";
                echo (($this->getAttribute($this->getContext($context, "filter"), "isActive")) ? ("active") : ("inactive"));
                echo "\">
                                <td class=\"filter-title\">";
                // line 70
                echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "admin"), "trans", array(0 => $this->getAttribute($this->getContext($context, "filter"), "label")), "method"), "html", null, true);
                echo "</td>
                                <td class=\"filter-type\">";
                // line 71
                echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute($this->getAttribute($this->getAttribute($this->getAttribute($this->getContext($context, "form"), "children"), $this->getAttribute($this->getContext($context, "filter"), "formName"), array(), "array"), "children"), "type", array(), "array"), 'widget');
                echo "</td>
                                <td class=\"filter-value\">";
                // line 72
                echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute($this->getAttribute($this->getAttribute($this->getAttribute($this->getContext($context, "form"), "children"), $this->getAttribute($this->getContext($context, "filter"), "formName"), array(), "array"), "children"), "value", array(), "array"), 'widget');
                echo "</td>
                            </tr>
                        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['filter'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 75
            echo "                    </table>

                    <input type=\"hidden\" name=\"filter[_page]\" id=\"filter__page\" value=\"1\" />

                    ";
            // line 79
            $context["foo"] = $this->getAttribute($this->getAttribute($this->getAttribute($this->getContext($context, "form"), "children"), "_page", array(), "array"), "setRendered", array(), "method");
            // line 80
            echo "                    ";
            echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getContext($context, "form"), 'rest');
            echo "

                    <input type=\"submit\" class=\"btn btn-primary\" value=\"";
            // line 82
            echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("btn_filter", array(), "SonataAdminBundle"), "html", null, true);
            echo "\" />

                    <a class=\"btn\" href=\"";
            // line 84
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "admin"), "generateUrl", array(0 => "list"), "method"), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("link_reset_filter", array(), "SonataAdminBundle"), "html", null, true);
            echo "</a>

                </div>
            </fieldset>

        </form>
    ";
        }
    }

    public function getTemplateName()
    {
        return "SonataMediaBundle:MediaAdmin:list.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  244 => 84,  192 => 66,  224 => 72,  388 => 154,  381 => 150,  365 => 142,  351 => 137,  346 => 136,  339 => 133,  323 => 124,  319 => 123,  315 => 122,  288 => 109,  270 => 100,  261 => 96,  239 => 82,  231 => 79,  215 => 76,  630 => 381,  628 => 377,  613 => 372,  608 => 370,  597 => 361,  593 => 360,  587 => 357,  583 => 356,  577 => 353,  542 => 330,  535 => 326,  531 => 324,  527 => 320,  463 => 284,  458 => 282,  446 => 275,  440 => 272,  428 => 265,  420 => 260,  398 => 243,  390 => 155,  342 => 134,  248 => 89,  183 => 83,  158 => 69,  295 => 135,  292 => 134,  281 => 167,  275 => 127,  237 => 110,  221 => 78,  202 => 72,  150 => 21,  160 => 23,  129 => 45,  264 => 98,  260 => 71,  226 => 81,  220 => 59,  199 => 52,  188 => 48,  177 => 42,  161 => 56,  156 => 55,  148 => 65,  124 => 43,  113 => 40,  99 => 23,  12 => 45,  638 => 211,  633 => 209,  625 => 206,  619 => 202,  606 => 198,  602 => 197,  591 => 195,  586 => 193,  579 => 191,  571 => 189,  568 => 188,  565 => 187,  545 => 103,  534 => 98,  529 => 321,  526 => 96,  514 => 85,  511 => 310,  507 => 108,  505 => 96,  499 => 84,  496 => 301,  493 => 82,  489 => 177,  469 => 168,  459 => 165,  442 => 161,  413 => 152,  410 => 253,  394 => 242,  389 => 143,  379 => 236,  370 => 229,  357 => 132,  347 => 130,  341 => 127,  301 => 137,  265 => 109,  262 => 157,  249 => 118,  217 => 136,  214 => 65,  210 => 64,  185 => 64,  182 => 82,  174 => 41,  117 => 39,  140 => 68,  97 => 91,  679 => 218,  676 => 217,  671 => 210,  665 => 207,  656 => 203,  651 => 201,  645 => 199,  643 => 389,  640 => 197,  634 => 383,  632 => 194,  629 => 193,  623 => 191,  621 => 190,  618 => 374,  612 => 187,  610 => 199,  604 => 368,  599 => 157,  595 => 196,  589 => 153,  580 => 150,  570 => 148,  567 => 346,  559 => 341,  553 => 111,  549 => 334,  546 => 109,  532 => 105,  524 => 103,  504 => 306,  501 => 94,  492 => 299,  485 => 292,  474 => 170,  471 => 85,  465 => 49,  461 => 166,  457 => 47,  448 => 44,  431 => 158,  425 => 36,  415 => 153,  411 => 31,  403 => 28,  393 => 156,  384 => 237,  382 => 184,  369 => 179,  360 => 140,  355 => 172,  352 => 171,  338 => 170,  332 => 123,  305 => 182,  299 => 161,  291 => 157,  287 => 155,  285 => 168,  278 => 142,  266 => 138,  252 => 119,  241 => 149,  235 => 130,  227 => 105,  222 => 138,  193 => 47,  179 => 61,  166 => 91,  137 => 88,  86 => 31,  335 => 94,  326 => 90,  306 => 87,  303 => 163,  283 => 107,  279 => 115,  276 => 165,  273 => 112,  271 => 163,  268 => 110,  259 => 81,  255 => 70,  245 => 115,  218 => 57,  211 => 98,  206 => 55,  190 => 69,  187 => 68,  169 => 80,  167 => 58,  164 => 57,  134 => 23,  77 => 73,  65 => 8,  56 => 23,  53 => 22,  686 => 206,  680 => 203,  677 => 202,  675 => 201,  669 => 198,  659 => 204,  654 => 202,  642 => 193,  639 => 192,  636 => 191,  627 => 207,  624 => 184,  607 => 185,  590 => 181,  585 => 179,  581 => 178,  578 => 177,  575 => 149,  572 => 175,  566 => 171,  562 => 146,  560 => 106,  555 => 167,  538 => 99,  521 => 317,  517 => 101,  512 => 99,  509 => 98,  506 => 160,  503 => 159,  500 => 158,  498 => 95,  495 => 156,  486 => 151,  482 => 172,  480 => 290,  477 => 147,  475 => 146,  472 => 287,  462 => 123,  453 => 46,  450 => 163,  437 => 138,  435 => 270,  432 => 136,  423 => 155,  421 => 35,  416 => 129,  405 => 127,  402 => 149,  400 => 248,  391 => 144,  377 => 148,  375 => 231,  371 => 145,  366 => 178,  356 => 139,  353 => 138,  343 => 128,  337 => 96,  331 => 92,  329 => 127,  324 => 119,  318 => 90,  312 => 89,  310 => 184,  302 => 116,  298 => 84,  289 => 133,  286 => 85,  274 => 77,  272 => 75,  269 => 125,  254 => 92,  250 => 67,  247 => 66,  243 => 87,  238 => 65,  236 => 63,  233 => 80,  208 => 70,  203 => 54,  200 => 61,  197 => 68,  175 => 60,  173 => 110,  112 => 40,  110 => 9,  90 => 35,  87 => 41,  69 => 11,  49 => 12,  23 => 1,  82 => 39,  62 => 25,  40 => 18,  20 => 1,  479 => 87,  473 => 161,  468 => 286,  460 => 155,  456 => 164,  452 => 151,  443 => 42,  439 => 41,  436 => 147,  434 => 159,  429 => 144,  426 => 157,  422 => 142,  412 => 134,  408 => 132,  406 => 252,  401 => 130,  397 => 129,  392 => 126,  386 => 115,  383 => 121,  380 => 112,  378 => 183,  373 => 116,  367 => 112,  364 => 177,  361 => 133,  359 => 106,  354 => 219,  340 => 97,  336 => 131,  321 => 91,  313 => 99,  311 => 166,  308 => 97,  304 => 95,  297 => 177,  293 => 158,  284 => 116,  282 => 144,  277 => 104,  267 => 99,  263 => 123,  257 => 121,  251 => 80,  246 => 76,  240 => 66,  234 => 63,  228 => 82,  223 => 58,  219 => 67,  213 => 99,  207 => 129,  198 => 123,  181 => 44,  176 => 62,  170 => 75,  168 => 60,  146 => 49,  142 => 29,  128 => 14,  125 => 51,  107 => 44,  38 => 6,  144 => 54,  141 => 19,  135 => 50,  126 => 56,  109 => 44,  103 => 45,  67 => 27,  61 => 31,  47 => 20,  105 => 9,  93 => 43,  76 => 36,  72 => 28,  68 => 9,  225 => 75,  216 => 72,  212 => 71,  205 => 54,  201 => 69,  196 => 71,  194 => 79,  191 => 119,  189 => 46,  186 => 88,  180 => 72,  172 => 16,  159 => 61,  154 => 20,  147 => 58,  132 => 49,  127 => 44,  121 => 54,  118 => 53,  114 => 38,  104 => 38,  100 => 66,  78 => 20,  75 => 14,  71 => 70,  58 => 23,  34 => 16,  27 => 16,  91 => 33,  84 => 35,  44 => 19,  25 => 14,  28 => 14,  24 => 14,  19 => 2,  94 => 34,  88 => 31,  79 => 31,  59 => 24,  31 => 15,  26 => 1,  21 => 12,  70 => 22,  63 => 23,  46 => 18,  22 => 14,  163 => 77,  155 => 51,  152 => 50,  149 => 53,  145 => 64,  139 => 61,  131 => 22,  123 => 43,  120 => 42,  115 => 75,  106 => 48,  101 => 45,  96 => 35,  83 => 15,  80 => 30,  74 => 15,  66 => 53,  55 => 58,  52 => 27,  50 => 21,  43 => 8,  41 => 11,  37 => 18,  35 => 7,  32 => 15,  29 => 14,  184 => 87,  178 => 52,  171 => 61,  165 => 58,  162 => 57,  157 => 31,  153 => 67,  151 => 42,  143 => 45,  138 => 47,  136 => 60,  133 => 58,  130 => 45,  122 => 42,  119 => 12,  116 => 11,  111 => 39,  108 => 10,  102 => 37,  98 => 47,  95 => 64,  92 => 44,  89 => 32,  85 => 38,  81 => 15,  73 => 39,  64 => 26,  60 => 6,  57 => 31,  54 => 12,  51 => 20,  48 => 22,  45 => 21,  42 => 10,  39 => 19,  36 => 17,  33 => 6,  30 => 15,);
    }
}
