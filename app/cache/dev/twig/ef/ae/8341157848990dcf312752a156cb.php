<?php

/* SonataAdminBundle:Core:create_button.html.twig */
class __TwigTemplate_efae8341157848990dcf312752a156cb extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 11
        echo "
";
        // line 12
        if (($this->getAttribute($this->getContext($context, "admin"), "hasRoute", array(0 => "create"), "method") && $this->getAttribute($this->getContext($context, "admin"), "isGranted", array(0 => "CREATE"), "method"))) {
            // line 13
            echo "    ";
            if (twig_test_empty($this->getAttribute($this->getContext($context, "admin"), "subClasses"))) {
                // line 14
                echo "        <a class=\"btn sonata-action-element\" href=\"";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "admin"), "generateUrl", array(0 => "create"), "method"), "html", null, true);
                echo "\">
            <i class=\"icon-plus\"></i>
            ";
                // line 16
                echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("link_action_create", array(), "SonataAdminBundle"), "html", null, true);
                echo "</a>
    ";
            } else {
                // line 18
                echo "        <span class=\"btn-group sonata-action-element\">
            <a class=\"btn dropdown-toggle\" data-toggle=\"dropdown\" href=\"#\">
                <i class=\"icon-plus\"></i>
                ";
                // line 21
                echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("link_action_create", array(), "SonataAdminBundle"), "html", null, true);
                echo "
                <span class=\"caret\"></span>
            </a>
            <ul class=\"dropdown-menu\">
                ";
                // line 25
                $context['_parent'] = (array) $context;
                $context['_seq'] = twig_ensure_traversable(twig_get_array_keys_filter($this->getAttribute($this->getContext($context, "admin"), "subclasses")));
                foreach ($context['_seq'] as $context["_key"] => $context["subclass"]) {
                    // line 26
                    echo "                    <li>
                        <a href=\"";
                    // line 27
                    echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "admin"), "generateUrl", array(0 => "create", 1 => array("subclass" => $this->getContext($context, "subclass"))), "method"), "html", null, true);
                    echo "\">";
                    echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans($this->getContext($context, "subclass"), array(), $this->getAttribute($this->getContext($context, "admin"), "translationdomain")), "html", null, true);
                    echo "</a>
                    </li>
                ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['subclass'], $context['_parent'], $context['loop']);
                $context = array_merge($_parent, array_intersect_key($context, $_parent));
                // line 30
                echo "            </ul>
        </span>
    ";
            }
        }
    }

    public function getTemplateName()
    {
        return "SonataAdminBundle:Core:create_button.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  68 => 30,  50 => 25,  27 => 14,  24 => 13,  21 => 2,  39 => 3,  22 => 12,  51 => 21,  34 => 16,  66 => 31,  64 => 30,  61 => 29,  56 => 26,  49 => 23,  45 => 19,  33 => 16,  28 => 14,  52 => 20,  47 => 25,  37 => 17,  31 => 15,  29 => 4,  20 => 11,  23 => 12,  19 => 11,  638 => 211,  633 => 209,  627 => 207,  625 => 206,  619 => 202,  610 => 199,  606 => 198,  602 => 197,  595 => 196,  591 => 195,  586 => 193,  579 => 191,  571 => 189,  568 => 188,  565 => 187,  560 => 106,  549 => 104,  545 => 103,  538 => 99,  534 => 98,  529 => 97,  526 => 96,  514 => 85,  511 => 84,  507 => 108,  505 => 96,  501 => 94,  499 => 84,  496 => 83,  493 => 82,  489 => 177,  482 => 172,  474 => 170,  472 => 169,  469 => 168,  461 => 166,  459 => 165,  456 => 164,  450 => 163,  442 => 161,  434 => 159,  431 => 158,  426 => 157,  423 => 155,  415 => 153,  413 => 152,  410 => 151,  402 => 149,  400 => 148,  394 => 145,  391 => 144,  389 => 143,  384 => 140,  379 => 137,  370 => 134,  361 => 133,  357 => 132,  353 => 131,  347 => 130,  343 => 128,  341 => 127,  332 => 123,  329 => 122,  324 => 119,  310 => 118,  301 => 117,  284 => 116,  279 => 115,  277 => 114,  273 => 112,  268 => 110,  265 => 109,  262 => 82,  259 => 81,  257 => 80,  252 => 78,  249 => 77,  246 => 76,  241 => 73,  234 => 71,  228 => 70,  222 => 68,  219 => 67,  217 => 66,  214 => 65,  210 => 64,  207 => 63,  203 => 62,  200 => 61,  197 => 60,  191 => 56,  185 => 55,  182 => 54,  178 => 52,  174 => 51,  169 => 50,  163 => 49,  151 => 48,  149 => 47,  146 => 46,  143 => 45,  140 => 44,  137 => 43,  134 => 42,  131 => 41,  128 => 40,  125 => 39,  122 => 38,  119 => 37,  117 => 36,  111 => 32,  108 => 31,  104 => 30,  100 => 28,  97 => 27,  89 => 182,  86 => 181,  81 => 178,  79 => 76,  76 => 75,  74 => 60,  71 => 34,  69 => 33,  63 => 25,  60 => 24,  57 => 27,  54 => 26,  48 => 20,  43 => 21,  41 => 23,  38 => 18,  35 => 17,);
    }
}
