<?php

/* SonataMediaBundle:Provider:view_image.html.twig */
class __TwigTemplate_abad8807d6f4a3ae206ee5cf97813189 extends Twig_Template
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
<img ";
        // line 12
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getContext($context, "options"));
        foreach ($context['_seq'] as $context["name"] => $context["value"]) {
            echo twig_escape_filter($this->env, $this->getContext($context, "name"), "html", null, true);
            echo "=\"";
            echo twig_escape_filter($this->env, $this->getContext($context, "value"), "html", null, true);
            echo "\" ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['name'], $context['value'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        echo " />
";
    }

    public function getTemplateName()
    {
        return "SonataMediaBundle:Provider:view_image.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  60 => 17,  58 => 16,  49 => 13,  42 => 10,  34 => 8,  46 => 16,  39 => 12,  30 => 6,  25 => 4,  21 => 2,  55 => 15,  52 => 15,  47 => 12,  41 => 10,  36 => 8,  33 => 7,  27 => 4,  24 => 3,  22 => 12,  19 => 11,  285 => 106,  279 => 97,  274 => 96,  271 => 95,  266 => 73,  248 => 55,  245 => 54,  236 => 48,  232 => 47,  228 => 46,  224 => 45,  220 => 44,  213 => 41,  203 => 34,  196 => 31,  189 => 21,  185 => 20,  180 => 19,  177 => 18,  171 => 15,  167 => 14,  163 => 13,  158 => 12,  155 => 11,  149 => 9,  136 => 125,  116 => 107,  114 => 106,  105 => 99,  103 => 95,  73 => 70,  71 => 54,  68 => 53,  66 => 19,  61 => 19,  50 => 14,  48 => 18,  45 => 17,  43 => 9,  28 => 5,  83 => 35,  80 => 74,  78 => 73,  74 => 32,  69 => 29,  65 => 28,  62 => 27,  59 => 31,  38 => 9,  32 => 7,  29 => 3,);
    }
}
