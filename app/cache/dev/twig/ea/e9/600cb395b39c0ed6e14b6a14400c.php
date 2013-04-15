<?php

/* TwigBundle:Exception:exception_full.html.twig */
class __TwigTemplate_eae9600cb395b39c0ed6e14b6a14400c extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("TwigBundle::layout.html.twig");

        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'body' => array($this, 'block_body'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "TwigBundle::layout.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_title($context, array $blocks = array())
    {
        // line 4
        echo "    ";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "exception"), "message"), "html", null, true);
        echo " (";
        echo twig_escape_filter($this->env, $this->getContext($context, "status_code"), "html", null, true);
        echo " ";
        echo twig_escape_filter($this->env, $this->getContext($context, "status_text"), "html", null, true);
        echo ")
";
    }

    // line 7
    public function block_body($context, array $blocks = array())
    {
        // line 8
        echo "    ";
        $this->env->loadTemplate("TwigBundle:Exception:exception.html.twig")->display($context);
    }

    public function getTemplateName()
    {
        return "TwigBundle:Exception:exception_full.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  32 => 4,  29 => 3,  224 => 72,  206 => 55,  203 => 54,  193 => 47,  189 => 46,  185 => 45,  181 => 44,  174 => 41,  164 => 34,  157 => 31,  150 => 21,  146 => 20,  141 => 19,  138 => 18,  132 => 15,  128 => 14,  124 => 13,  119 => 12,  116 => 11,  110 => 9,  97 => 91,  77 => 73,  75 => 72,  71 => 70,  69 => 54,  66 => 53,  64 => 41,  59 => 38,  57 => 31,  48 => 24,  46 => 8,  41 => 11,  36 => 9,  26 => 1,  80 => 31,  67 => 24,  60 => 19,  58 => 18,  51 => 16,  43 => 7,  39 => 9,  31 => 3,  28 => 2,);
    }
}
