<?php

/* SonataAdminBundle:CRUD:base_list_flat_field.html.twig */
class __TwigTemplate_8002987cef090c3099d1952d2d474094 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'field' => array($this, 'block_field'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 11
        echo "
<span class=\"sonata-ba-list-field sonata-ba-list-field-";
        // line 12
        echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "field_description"), "type"), "html", null, true);
        echo "\" objectId=\"";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "admin"), "id", array(0 => $this->getContext($context, "object")), "method"), "html", null, true);
        echo "\">
    ";
        // line 13
        if (((($this->getAttribute($this->getAttribute($this->getContext($context, "field_description", true), "options", array(), "any", false, true), "identifier", array(), "any", true, true) && $this->getAttribute($this->getAttribute($this->getContext($context, "field_description", true), "options", array(), "any", false, true), "route", array(), "any", true, true)) && $this->getAttribute($this->getContext($context, "admin"), "isGranted", array(0 => ((($this->getAttribute($this->getAttribute($this->getAttribute($this->getContext($context, "field_description"), "options"), "route"), "name") == "show")) ? ("VIEW") : (twig_upper_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute($this->getContext($context, "field_description"), "options"), "route"), "name")))), 1 => $this->getContext($context, "object")), "method")) && $this->getAttribute($this->getContext($context, "admin"), "hasRoute", array(0 => $this->getAttribute($this->getAttribute($this->getAttribute($this->getContext($context, "field_description"), "options"), "route"), "name")), "method"))) {
            // line 19
            echo "        <a href=\"";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "admin"), "generateObjectUrl", array(0 => $this->getAttribute($this->getAttribute($this->getAttribute($this->getContext($context, "field_description"), "options"), "route"), "name"), 1 => $this->getContext($context, "object"), 2 => $this->getAttribute($this->getAttribute($this->getAttribute($this->getContext($context, "field_description"), "options"), "route"), "parameters")), "method"), "html", null, true);
            echo "\">";
            // line 20
            $this->displayBlock('field', $context, $blocks);
            // line 21
            echo "</a>
    ";
        } else {
            // line 23
            echo "        ";
            $this->displayBlock("field", $context, $blocks);
            echo "
    ";
        }
        // line 25
        echo "</span>
";
    }

    // line 20
    public function block_field($context, array $blocks = array())
    {
        echo twig_escape_filter($this->env, $this->getContext($context, "value"), "html", null, true);
    }

    public function getTemplateName()
    {
        return "SonataAdminBundle:CRUD:base_list_flat_field.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  333 => 85,  330 => 84,  325 => 82,  299 => 75,  290 => 72,  288 => 71,  270 => 65,  267 => 64,  261 => 62,  244 => 56,  239 => 51,  231 => 27,  213 => 23,  170 => 89,  159 => 54,  150 => 48,  124 => 47,  113 => 40,  12 => 45,  631 => 203,  625 => 201,  623 => 200,  617 => 196,  608 => 193,  604 => 192,  595 => 190,  591 => 189,  579 => 185,  571 => 183,  568 => 182,  565 => 181,  549 => 98,  545 => 97,  534 => 92,  529 => 91,  526 => 90,  514 => 79,  511 => 78,  505 => 90,  501 => 88,  496 => 77,  493 => 76,  489 => 171,  459 => 159,  442 => 155,  434 => 153,  431 => 152,  415 => 147,  410 => 145,  394 => 139,  332 => 117,  301 => 76,  284 => 110,  265 => 103,  262 => 76,  257 => 74,  249 => 71,  185 => 55,  174 => 51,  613 => 205,  610 => 204,  605 => 199,  598 => 191,  592 => 192,  588 => 190,  586 => 187,  583 => 188,  577 => 186,  564 => 181,  561 => 180,  553 => 177,  550 => 176,  544 => 174,  542 => 173,  539 => 172,  536 => 171,  531 => 159,  527 => 128,  507 => 102,  502 => 122,  499 => 78,  494 => 120,  491 => 119,  485 => 110,  481 => 109,  478 => 108,  470 => 105,  464 => 104,  449 => 100,  441 => 97,  436 => 96,  433 => 95,  424 => 93,  417 => 87,  411 => 86,  406 => 85,  403 => 84,  397 => 48,  389 => 137,  370 => 128,  367 => 39,  361 => 127,  357 => 126,  351 => 32,  347 => 124,  342 => 29,  339 => 28,  336 => 27,  327 => 204,  320 => 171,  316 => 80,  313 => 79,  307 => 166,  304 => 165,  296 => 74,  253 => 147,  248 => 144,  242 => 142,  234 => 140,  232 => 139,  226 => 65,  205 => 135,  198 => 132,  179 => 111,  155 => 81,  127 => 52,  335 => 94,  326 => 90,  306 => 77,  303 => 86,  279 => 68,  276 => 151,  273 => 66,  271 => 79,  259 => 75,  255 => 60,  245 => 66,  240 => 64,  218 => 57,  211 => 53,  206 => 21,  190 => 50,  181 => 47,  172 => 44,  167 => 42,  134 => 42,  65 => 5,  63 => 16,  686 => 206,  680 => 203,  677 => 202,  675 => 201,  669 => 198,  659 => 197,  654 => 195,  642 => 193,  639 => 192,  636 => 205,  627 => 185,  624 => 184,  607 => 182,  590 => 181,  585 => 179,  581 => 178,  578 => 177,  575 => 185,  572 => 184,  566 => 182,  562 => 169,  560 => 100,  555 => 178,  538 => 93,  521 => 127,  517 => 163,  512 => 124,  509 => 161,  506 => 160,  503 => 159,  500 => 158,  498 => 157,  495 => 156,  486 => 151,  482 => 166,  477 => 147,  475 => 146,  472 => 163,  468 => 125,  462 => 123,  456 => 158,  450 => 157,  443 => 140,  432 => 136,  426 => 151,  421 => 131,  416 => 129,  405 => 127,  400 => 142,  391 => 138,  386 => 115,  380 => 112,  377 => 111,  375 => 41,  371 => 109,  366 => 107,  359 => 106,  356 => 105,  353 => 125,  343 => 122,  340 => 97,  331 => 92,  329 => 116,  324 => 113,  321 => 81,  318 => 90,  310 => 112,  302 => 164,  289 => 156,  286 => 85,  282 => 69,  277 => 108,  274 => 77,  272 => 76,  269 => 75,  263 => 71,  254 => 68,  250 => 58,  243 => 65,  238 => 64,  236 => 50,  233 => 62,  228 => 59,  223 => 64,  200 => 61,  197 => 60,  191 => 56,  184 => 114,  178 => 17,  175 => 16,  146 => 46,  118 => 49,  110 => 63,  104 => 42,  90 => 39,  81 => 172,  59 => 29,  23 => 12,  76 => 69,  58 => 12,  53 => 10,  20 => 11,  152 => 35,  148 => 63,  137 => 43,  102 => 41,  100 => 28,  97 => 40,  77 => 27,  34 => 18,  480 => 148,  474 => 164,  469 => 162,  461 => 160,  457 => 153,  453 => 101,  444 => 98,  440 => 148,  437 => 138,  435 => 137,  430 => 94,  427 => 143,  423 => 149,  413 => 146,  409 => 132,  407 => 131,  402 => 143,  398 => 129,  393 => 47,  387 => 122,  384 => 134,  381 => 120,  379 => 131,  374 => 116,  368 => 112,  365 => 111,  362 => 110,  360 => 109,  355 => 106,  341 => 121,  337 => 96,  322 => 201,  314 => 99,  312 => 89,  309 => 97,  305 => 95,  298 => 84,  294 => 159,  285 => 154,  283 => 84,  278 => 86,  268 => 104,  264 => 84,  258 => 81,  252 => 59,  247 => 57,  241 => 67,  235 => 74,  229 => 138,  224 => 71,  220 => 63,  214 => 69,  208 => 136,  169 => 50,  143 => 45,  140 => 56,  132 => 51,  128 => 40,  119 => 51,  107 => 24,  71 => 25,  38 => 14,  177 => 65,  165 => 64,  160 => 66,  135 => 47,  126 => 51,  114 => 49,  84 => 36,  70 => 20,  67 => 33,  61 => 26,  94 => 39,  89 => 37,  85 => 36,  75 => 32,  68 => 24,  56 => 24,  196 => 90,  183 => 70,  171 => 93,  166 => 87,  163 => 49,  158 => 67,  156 => 53,  151 => 48,  142 => 59,  138 => 57,  136 => 56,  121 => 41,  117 => 36,  105 => 36,  91 => 38,  62 => 30,  49 => 28,  87 => 17,  21 => 11,  31 => 19,  25 => 12,  26 => 14,  28 => 14,  24 => 13,  19 => 11,  93 => 41,  88 => 6,  78 => 21,  46 => 9,  44 => 74,  27 => 14,  79 => 34,  72 => 19,  69 => 34,  47 => 25,  40 => 24,  37 => 21,  22 => 11,  246 => 70,  157 => 82,  145 => 46,  139 => 31,  131 => 55,  123 => 52,  120 => 21,  115 => 48,  111 => 38,  108 => 44,  101 => 44,  98 => 21,  96 => 42,  83 => 35,  74 => 31,  66 => 17,  55 => 23,  52 => 20,  50 => 22,  43 => 25,  41 => 23,  35 => 20,  32 => 15,  29 => 13,  209 => 82,  203 => 62,  199 => 20,  193 => 129,  189 => 71,  187 => 49,  182 => 19,  176 => 110,  173 => 94,  168 => 66,  164 => 56,  162 => 62,  154 => 50,  149 => 47,  147 => 58,  144 => 32,  141 => 61,  133 => 54,  130 => 66,  125 => 51,  122 => 50,  116 => 36,  112 => 47,  109 => 47,  106 => 44,  103 => 45,  99 => 40,  95 => 39,  92 => 28,  86 => 25,  82 => 23,  80 => 25,  73 => 30,  64 => 31,  60 => 15,  57 => 14,  54 => 26,  51 => 29,  48 => 15,  45 => 26,  42 => 19,  39 => 25,  36 => 18,  33 => 17,  30 => 15,);
    }
}
