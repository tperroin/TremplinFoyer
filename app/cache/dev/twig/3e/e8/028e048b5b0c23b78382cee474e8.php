<?php

/* FOSUserBundle:Registration:checkEmail.html.twig */
class __TwigTemplate_3ee8028e048b5b0c23b78382cee474e8 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("FOSUserBundle::layout.html.twig");

        $this->blocks = array(
            'fos_user_content' => array($this, 'block_fos_user_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "FOSUserBundle::layout.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_fos_user_content($context, array $blocks = array())
    {
        // line 4
        echo "    <p>";
        echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("registration.check_email", array("%email%" => $this->getAttribute($this->getContext($context, "user"), "email")), "FOSUserBundle"), "html", null, true);
        echo "</p>
";
    }

    public function getTemplateName()
    {
        return "FOSUserBundle:Registration:checkEmail.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  23 => 3,  76 => 23,  58 => 18,  53 => 23,  20 => 1,  152 => 64,  148 => 63,  137 => 60,  102 => 41,  100 => 40,  97 => 39,  77 => 28,  34 => 5,  480 => 162,  474 => 161,  469 => 158,  461 => 155,  457 => 153,  453 => 151,  444 => 149,  440 => 148,  437 => 147,  435 => 146,  430 => 144,  427 => 143,  423 => 142,  413 => 134,  409 => 132,  407 => 131,  402 => 130,  398 => 129,  393 => 126,  387 => 122,  384 => 121,  381 => 120,  379 => 119,  374 => 116,  368 => 112,  365 => 111,  362 => 110,  360 => 109,  355 => 106,  341 => 105,  337 => 103,  322 => 101,  314 => 99,  312 => 98,  309 => 97,  305 => 95,  298 => 91,  294 => 90,  285 => 89,  283 => 88,  278 => 86,  268 => 85,  264 => 84,  258 => 81,  252 => 80,  247 => 78,  241 => 77,  235 => 74,  229 => 73,  224 => 71,  220 => 70,  214 => 69,  208 => 68,  169 => 60,  143 => 56,  140 => 55,  132 => 51,  128 => 49,  119 => 42,  107 => 36,  71 => 19,  38 => 6,  177 => 65,  165 => 64,  160 => 66,  135 => 47,  126 => 51,  114 => 42,  84 => 28,  70 => 20,  67 => 18,  61 => 18,  94 => 38,  89 => 25,  85 => 25,  75 => 23,  68 => 22,  56 => 9,  196 => 90,  183 => 70,  171 => 61,  166 => 71,  163 => 70,  158 => 67,  156 => 65,  151 => 57,  142 => 59,  138 => 57,  136 => 56,  121 => 46,  117 => 47,  105 => 40,  91 => 31,  62 => 19,  49 => 19,  87 => 20,  21 => 2,  31 => 4,  25 => 3,  26 => 2,  28 => 3,  24 => 6,  19 => 1,  93 => 28,  88 => 6,  78 => 21,  46 => 9,  44 => 19,  27 => 4,  79 => 18,  72 => 20,  69 => 25,  47 => 9,  40 => 11,  37 => 18,  22 => 1,  246 => 32,  157 => 56,  145 => 46,  139 => 50,  131 => 42,  123 => 50,  120 => 40,  115 => 43,  111 => 45,  108 => 37,  101 => 32,  98 => 31,  96 => 31,  83 => 25,  74 => 14,  66 => 24,  55 => 12,  52 => 20,  50 => 8,  43 => 8,  41 => 7,  35 => 5,  32 => 12,  29 => 8,  209 => 82,  203 => 78,  199 => 67,  193 => 73,  189 => 71,  187 => 84,  182 => 66,  176 => 64,  173 => 70,  168 => 66,  164 => 67,  162 => 62,  154 => 54,  149 => 51,  147 => 58,  144 => 62,  141 => 61,  133 => 55,  130 => 41,  125 => 44,  122 => 43,  116 => 36,  112 => 42,  109 => 44,  106 => 36,  103 => 37,  99 => 30,  95 => 34,  92 => 26,  86 => 33,  82 => 27,  80 => 25,  73 => 19,  64 => 17,  60 => 14,  57 => 11,  54 => 16,  51 => 11,  48 => 11,  45 => 20,  42 => 8,  39 => 6,  36 => 5,  33 => 3,  30 => 1,);
    }
}
