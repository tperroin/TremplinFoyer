<?php

/* FOSUserBundle:Registration:email.txt.twig */
class __TwigTemplate_f0598d63b46d408005e65b109bec5553 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'subject' => array($this, 'block_subject'),
            'body_text' => array($this, 'block_body_text'),
            'body_html' => array($this, 'block_body_html'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        $this->displayBlock('subject', $context, $blocks);
        // line 6
        $this->displayBlock('body_text', $context, $blocks);
        // line 11
        $this->displayBlock('body_html', $context, $blocks);
    }

    // line 1
    public function block_subject($context, array $blocks = array())
    {
        // line 3
        echo $this->env->getExtension('translator')->trans("registration.email.subject", array("%username%" => $this->getAttribute($this->getContext($context, "user"), "username"), "%confirmationUrl%" => $this->getContext($context, "confirmationUrl")), "FOSUserBundle");
        echo "
";
    }

    // line 6
    public function block_body_text($context, array $blocks = array())
    {
        // line 8
        echo $this->env->getExtension('translator')->trans("registration.email.message", array("%username%" => $this->getAttribute($this->getContext($context, "user"), "username"), "%confirmationUrl%" => $this->getContext($context, "confirmationUrl")), "FOSUserBundle");
        echo "
";
    }

    // line 11
    public function block_body_html($context, array $blocks = array())
    {
    }

    public function getTemplateName()
    {
        return "FOSUserBundle:Registration:email.txt.twig";
    }

    public function getDebugInfo()
    {
        return array (  23 => 3,  82 => 27,  62 => 19,  53 => 23,  40 => 11,  20 => 1,  173 => 70,  164 => 67,  160 => 66,  156 => 65,  148 => 63,  137 => 60,  117 => 47,  97 => 39,  86 => 33,  77 => 28,  479 => 162,  473 => 161,  468 => 158,  460 => 155,  456 => 153,  452 => 151,  443 => 149,  439 => 148,  436 => 147,  434 => 146,  429 => 144,  426 => 143,  422 => 142,  412 => 134,  408 => 132,  406 => 131,  401 => 130,  397 => 129,  392 => 126,  386 => 122,  383 => 121,  380 => 120,  378 => 119,  373 => 116,  367 => 112,  364 => 111,  361 => 110,  359 => 109,  354 => 106,  340 => 105,  336 => 103,  321 => 101,  313 => 99,  311 => 98,  308 => 97,  304 => 95,  297 => 91,  293 => 90,  284 => 89,  282 => 88,  277 => 86,  267 => 85,  263 => 84,  257 => 81,  251 => 80,  246 => 78,  240 => 77,  234 => 74,  228 => 73,  223 => 71,  219 => 70,  213 => 69,  207 => 68,  198 => 67,  181 => 66,  176 => 65,  170 => 61,  168 => 60,  146 => 58,  142 => 56,  128 => 50,  125 => 44,  107 => 36,  38 => 6,  144 => 62,  141 => 61,  135 => 47,  126 => 51,  109 => 44,  103 => 37,  67 => 18,  61 => 18,  47 => 9,  105 => 24,  93 => 28,  76 => 23,  72 => 20,  68 => 22,  225 => 96,  216 => 90,  212 => 88,  205 => 84,  201 => 83,  196 => 80,  194 => 79,  191 => 78,  189 => 77,  186 => 76,  180 => 72,  172 => 67,  159 => 61,  154 => 59,  147 => 55,  132 => 48,  127 => 46,  121 => 45,  118 => 44,  114 => 42,  104 => 36,  100 => 40,  78 => 21,  75 => 23,  71 => 19,  58 => 18,  34 => 5,  27 => 4,  91 => 20,  84 => 28,  44 => 19,  25 => 3,  28 => 3,  24 => 6,  19 => 1,  94 => 38,  88 => 6,  79 => 17,  59 => 22,  31 => 4,  26 => 11,  21 => 2,  70 => 20,  63 => 24,  46 => 9,  22 => 1,  163 => 59,  155 => 58,  152 => 64,  149 => 48,  145 => 46,  139 => 55,  131 => 51,  123 => 50,  120 => 40,  115 => 39,  106 => 36,  101 => 32,  96 => 21,  83 => 18,  80 => 25,  74 => 16,  66 => 15,  55 => 12,  52 => 20,  50 => 8,  43 => 7,  41 => 7,  37 => 18,  35 => 5,  32 => 12,  29 => 8,  184 => 70,  178 => 71,  171 => 62,  165 => 58,  162 => 57,  157 => 60,  153 => 54,  151 => 53,  143 => 54,  138 => 51,  136 => 50,  133 => 43,  130 => 47,  122 => 43,  119 => 42,  116 => 35,  111 => 45,  108 => 37,  102 => 41,  98 => 31,  95 => 34,  92 => 26,  89 => 25,  85 => 25,  81 => 40,  73 => 19,  64 => 17,  60 => 14,  57 => 11,  54 => 16,  51 => 11,  48 => 11,  45 => 20,  42 => 8,  39 => 6,  36 => 5,  33 => 3,  30 => 1,);
    }
}
