<?php

/* FOSUserBundle:Resetting:request_content.html.twig */
class __TwigTemplate_e2bf3ec3a29f3a6cb95bf8b2b9f863ac extends Twig_Template
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
        echo "<form action=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("fos_user_resetting_send_email"), "html", null, true);
        echo "\" method=\"POST\" class=\"fos_user_resetting_request\">
    <div>
        ";
        // line 3
        if (array_key_exists("invalid_username", $context)) {
            // line 4
            echo "            <p>";
            echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("resetting.request.invalid_username", array("%username%" => $this->getContext($context, "invalid_username")), "FOSUserBundle"), "html", null, true);
            echo "</p>
        ";
        }
        // line 6
        echo "        <label for=\"username\">";
        echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("resetting.request.username", array(), "FOSUserBundle"), "html", null, true);
        echo "</label>
        <input type=\"text\" id=\"username\" name=\"username\" required=\"required\" />
    </div>
    <div>
        <input type=\"submit\" value=\"";
        // line 10
        echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("resetting.request.submit", array(), "FOSUserBundle"), "html", null, true);
        echo "\" />
    </div>
</form>
";
    }

    public function getTemplateName()
    {
        return "FOSUserBundle:Resetting:request_content.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  53 => 23,  40 => 19,  20 => 11,  173 => 70,  164 => 67,  160 => 66,  156 => 65,  148 => 63,  137 => 60,  117 => 47,  97 => 39,  86 => 33,  77 => 28,  479 => 162,  473 => 161,  468 => 158,  460 => 155,  456 => 153,  452 => 151,  443 => 149,  439 => 148,  436 => 147,  434 => 146,  429 => 144,  426 => 143,  422 => 142,  412 => 134,  408 => 132,  406 => 131,  401 => 130,  397 => 129,  392 => 126,  386 => 122,  383 => 121,  380 => 120,  378 => 119,  373 => 116,  367 => 112,  364 => 111,  361 => 110,  359 => 109,  354 => 106,  340 => 105,  336 => 103,  321 => 101,  313 => 99,  311 => 98,  308 => 97,  304 => 95,  297 => 91,  293 => 90,  284 => 89,  282 => 88,  277 => 86,  267 => 85,  263 => 84,  257 => 81,  251 => 80,  246 => 78,  240 => 77,  234 => 74,  228 => 73,  223 => 71,  219 => 70,  213 => 69,  207 => 68,  198 => 67,  181 => 66,  176 => 65,  170 => 61,  168 => 60,  146 => 58,  142 => 56,  128 => 50,  125 => 44,  107 => 36,  38 => 6,  144 => 62,  141 => 61,  135 => 47,  126 => 51,  109 => 44,  103 => 37,  67 => 25,  61 => 18,  47 => 9,  105 => 24,  93 => 28,  76 => 16,  72 => 14,  68 => 22,  225 => 96,  216 => 90,  212 => 88,  205 => 84,  201 => 83,  196 => 80,  194 => 79,  191 => 78,  189 => 77,  186 => 76,  180 => 72,  172 => 67,  159 => 61,  154 => 59,  147 => 55,  132 => 48,  127 => 46,  121 => 45,  118 => 44,  114 => 42,  104 => 36,  100 => 40,  78 => 21,  75 => 23,  71 => 19,  58 => 9,  34 => 4,  27 => 4,  91 => 20,  84 => 28,  44 => 19,  25 => 3,  28 => 3,  24 => 3,  19 => 1,  94 => 38,  88 => 6,  79 => 17,  59 => 22,  31 => 3,  26 => 13,  21 => 2,  70 => 20,  63 => 24,  46 => 7,  22 => 2,  163 => 59,  155 => 58,  152 => 64,  149 => 48,  145 => 46,  139 => 55,  131 => 51,  123 => 50,  120 => 40,  115 => 39,  106 => 36,  101 => 32,  96 => 21,  83 => 18,  80 => 29,  74 => 16,  66 => 15,  55 => 15,  52 => 20,  50 => 22,  43 => 7,  41 => 10,  37 => 18,  35 => 5,  32 => 12,  29 => 14,  184 => 70,  178 => 71,  171 => 62,  165 => 58,  162 => 57,  157 => 60,  153 => 54,  151 => 53,  143 => 54,  138 => 51,  136 => 50,  133 => 43,  130 => 47,  122 => 43,  119 => 42,  116 => 35,  111 => 45,  108 => 37,  102 => 41,  98 => 31,  95 => 34,  92 => 33,  89 => 19,  85 => 25,  81 => 40,  73 => 19,  64 => 17,  60 => 23,  57 => 11,  54 => 14,  51 => 14,  48 => 13,  45 => 20,  42 => 7,  39 => 9,  36 => 17,  33 => 6,  30 => 7,);
    }
}