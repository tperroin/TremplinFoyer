<?php

/* TwigBundle:Exception:logs.html.twig */
class __TwigTemplate_9dc40f6bdd4c337c33a1bd8cf1c10877 extends Twig_Template
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
        // line 1
        echo "<ol class=\"traces logs\">
    ";
        // line 2
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getContext($context, "logs"));
        foreach ($context['_seq'] as $context["_key"] => $context["log"]) {
            // line 3
            echo "        <li";
            if (($this->getAttribute($this->getContext($context, "log"), "priority") >= 400)) {
                echo " class=\"error\"";
            } elseif (($this->getAttribute($this->getContext($context, "log"), "priority") >= 300)) {
                echo " class=\"warning\"";
            }
            echo ">
            ";
            // line 4
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "log"), "priorityName"), "html", null, true);
            echo " - ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "log"), "message"), "html", null, true);
            echo "
        </li>
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['log'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 7
        echo "</ol>
";
    }

    public function getTemplateName()
    {
        return "TwigBundle:Exception:logs.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  91 => 20,  84 => 19,  74 => 16,  55 => 13,  44 => 10,  25 => 4,  105 => 24,  98 => 22,  96 => 21,  93 => 20,  89 => 19,  83 => 18,  76 => 16,  72 => 14,  68 => 12,  50 => 8,  33 => 5,  27 => 4,  24 => 3,  22 => 2,  225 => 96,  216 => 90,  212 => 88,  205 => 84,  201 => 83,  196 => 80,  194 => 79,  191 => 78,  186 => 76,  180 => 72,  178 => 71,  172 => 67,  163 => 63,  159 => 61,  154 => 59,  147 => 55,  143 => 54,  136 => 50,  130 => 47,  127 => 46,  121 => 45,  118 => 44,  114 => 43,  104 => 36,  100 => 34,  95 => 31,  78 => 28,  63 => 24,  34 => 11,  19 => 1,  94 => 39,  88 => 6,  81 => 40,  79 => 17,  35 => 4,  21 => 2,  32 => 4,  29 => 3,  224 => 72,  206 => 55,  203 => 54,  193 => 47,  189 => 77,  185 => 45,  181 => 44,  174 => 41,  164 => 34,  157 => 60,  150 => 21,  146 => 20,  141 => 19,  138 => 51,  132 => 48,  128 => 14,  124 => 13,  119 => 12,  116 => 11,  110 => 9,  97 => 91,  77 => 73,  75 => 27,  71 => 26,  69 => 54,  66 => 15,  64 => 41,  59 => 22,  57 => 14,  48 => 19,  46 => 7,  41 => 9,  36 => 7,  26 => 3,  80 => 31,  67 => 24,  60 => 23,  58 => 9,  51 => 12,  43 => 7,  39 => 6,  31 => 5,  28 => 2,);
    }
}
