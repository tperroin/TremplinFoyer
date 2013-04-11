<?php

/* SonataAdminBundle:CRUD:show_array.html.twig */
class __TwigTemplate_b69d5e8fb6ee01e4a9e1abe5748e0ae4 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("SonataAdminBundle:CRUD:base_show_field.html.twig");

        $this->blocks = array(
            'field' => array($this, 'block_field'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "SonataAdminBundle:CRUD:base_show_field.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 14
    public function block_field($context, array $blocks = array())
    {
        // line 15
        echo "    ";
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getContext($context, "value"));
        foreach ($context['_seq'] as $context["key"] => $context["val"]) {
            // line 16
            echo "        ";
            if ($this->getAttribute($this->getAttribute($this->getContext($context, "field_description"), "options"), "safe")) {
                // line 17
                echo "            [";
                echo twig_escape_filter($this->env, $this->getContext($context, "key"), "html", null, true);
                echo " => ";
                echo $this->getContext($context, "val");
                echo "]
        ";
            } else {
                // line 19
                echo "            [";
                echo twig_escape_filter($this->env, $this->getContext($context, "key"), "html", null, true);
                echo " => ";
                echo twig_escape_filter($this->env, $this->getContext($context, "val"), "html", null, true);
                echo "]
        ";
            }
            // line 21
            echo "    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['key'], $context['val'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
    }

    public function getTemplateName()
    {
        return "SonataAdminBundle:CRUD:show_array.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  638 => 211,  633 => 209,  610 => 199,  602 => 197,  586 => 193,  579 => 191,  568 => 188,  565 => 187,  529 => 97,  526 => 96,  514 => 85,  511 => 84,  507 => 108,  501 => 94,  499 => 84,  496 => 83,  493 => 82,  489 => 177,  474 => 170,  469 => 168,  459 => 165,  442 => 161,  413 => 152,  410 => 151,  394 => 145,  389 => 143,  379 => 137,  370 => 134,  357 => 132,  347 => 130,  341 => 127,  301 => 117,  265 => 109,  262 => 82,  249 => 77,  217 => 66,  214 => 65,  210 => 64,  185 => 55,  182 => 54,  174 => 51,  117 => 36,  140 => 44,  97 => 27,  672 => 216,  667 => 209,  661 => 206,  655 => 203,  652 => 202,  650 => 201,  647 => 200,  641 => 198,  630 => 194,  628 => 193,  625 => 206,  619 => 202,  617 => 189,  614 => 188,  608 => 186,  606 => 198,  603 => 184,  600 => 183,  595 => 196,  591 => 195,  576 => 149,  571 => 189,  563 => 146,  558 => 145,  549 => 104,  545 => 103,  542 => 108,  534 => 98,  528 => 104,  520 => 102,  513 => 100,  508 => 98,  505 => 96,  497 => 95,  494 => 94,  488 => 93,  481 => 87,  470 => 85,  467 => 84,  461 => 166,  457 => 47,  448 => 44,  431 => 158,  425 => 36,  415 => 153,  411 => 31,  403 => 28,  393 => 221,  384 => 140,  382 => 183,  369 => 178,  360 => 174,  355 => 171,  352 => 170,  338 => 169,  332 => 123,  305 => 163,  299 => 160,  291 => 156,  287 => 154,  285 => 144,  278 => 141,  266 => 137,  252 => 78,  241 => 73,  235 => 129,  227 => 127,  222 => 68,  193 => 120,  179 => 111,  166 => 90,  137 => 43,  86 => 181,  335 => 94,  326 => 90,  306 => 87,  303 => 162,  283 => 84,  279 => 115,  276 => 81,  273 => 112,  271 => 79,  268 => 110,  259 => 81,  255 => 69,  245 => 66,  218 => 57,  211 => 53,  206 => 51,  190 => 50,  187 => 49,  169 => 50,  167 => 42,  164 => 84,  134 => 42,  77 => 8,  65 => 5,  56 => 27,  53 => 77,  686 => 206,  680 => 203,  677 => 202,  675 => 217,  669 => 198,  659 => 197,  654 => 195,  642 => 193,  639 => 197,  636 => 196,  627 => 207,  624 => 184,  607 => 182,  590 => 181,  585 => 152,  581 => 178,  578 => 177,  575 => 176,  572 => 175,  566 => 147,  562 => 169,  560 => 106,  555 => 144,  538 => 99,  521 => 164,  517 => 101,  512 => 162,  509 => 161,  506 => 160,  503 => 159,  500 => 96,  498 => 157,  495 => 156,  486 => 151,  482 => 172,  480 => 148,  477 => 147,  475 => 86,  472 => 169,  462 => 123,  453 => 46,  450 => 163,  437 => 138,  435 => 137,  432 => 136,  423 => 155,  421 => 35,  416 => 129,  405 => 127,  402 => 149,  400 => 148,  391 => 144,  377 => 111,  375 => 181,  371 => 109,  366 => 177,  356 => 105,  353 => 131,  343 => 128,  337 => 96,  331 => 92,  329 => 122,  324 => 119,  318 => 90,  312 => 89,  310 => 118,  302 => 86,  298 => 84,  289 => 81,  286 => 85,  274 => 77,  272 => 139,  269 => 138,  254 => 68,  250 => 67,  247 => 66,  243 => 65,  238 => 64,  236 => 63,  233 => 62,  208 => 57,  203 => 62,  200 => 61,  197 => 60,  175 => 45,  173 => 94,  112 => 48,  110 => 63,  90 => 18,  87 => 17,  69 => 27,  49 => 22,  23 => 12,  82 => 35,  62 => 156,  40 => 17,  20 => 11,  479 => 162,  473 => 161,  468 => 125,  460 => 155,  456 => 164,  452 => 151,  443 => 42,  439 => 41,  436 => 147,  434 => 159,  429 => 144,  426 => 157,  422 => 142,  412 => 134,  408 => 132,  406 => 29,  401 => 130,  397 => 129,  392 => 126,  386 => 115,  383 => 121,  380 => 112,  378 => 182,  373 => 116,  367 => 112,  364 => 176,  361 => 133,  359 => 106,  354 => 106,  340 => 97,  336 => 103,  321 => 91,  313 => 99,  311 => 165,  308 => 97,  304 => 95,  297 => 159,  293 => 157,  284 => 116,  282 => 143,  277 => 114,  267 => 85,  263 => 71,  257 => 80,  251 => 80,  246 => 76,  240 => 64,  234 => 71,  228 => 70,  223 => 58,  219 => 67,  213 => 69,  207 => 63,  198 => 122,  181 => 47,  176 => 110,  170 => 61,  168 => 60,  146 => 46,  142 => 56,  128 => 40,  125 => 39,  107 => 24,  38 => 15,  144 => 32,  141 => 51,  135 => 47,  126 => 45,  109 => 18,  103 => 60,  67 => 52,  61 => 17,  47 => 19,  105 => 24,  93 => 41,  76 => 75,  72 => 54,  68 => 6,  225 => 126,  216 => 90,  212 => 88,  205 => 84,  201 => 123,  196 => 121,  194 => 79,  191 => 56,  189 => 77,  186 => 76,  180 => 72,  172 => 44,  159 => 61,  154 => 59,  147 => 33,  132 => 48,  127 => 52,  121 => 29,  118 => 28,  114 => 42,  104 => 30,  100 => 28,  78 => 21,  75 => 23,  71 => 59,  58 => 16,  34 => 15,  27 => 14,  91 => 20,  84 => 16,  44 => 27,  25 => 3,  28 => 14,  24 => 12,  19 => 1,  94 => 39,  88 => 6,  79 => 76,  59 => 155,  31 => 15,  26 => 14,  21 => 2,  70 => 20,  63 => 25,  46 => 21,  22 => 11,  163 => 49,  155 => 81,  152 => 35,  149 => 47,  145 => 46,  139 => 31,  131 => 41,  123 => 51,  120 => 21,  115 => 49,  106 => 61,  101 => 43,  96 => 21,  83 => 58,  80 => 57,  74 => 60,  66 => 15,  55 => 21,  52 => 15,  50 => 75,  43 => 17,  41 => 16,  37 => 21,  35 => 14,  32 => 32,  29 => 13,  184 => 114,  178 => 52,  171 => 93,  165 => 58,  162 => 57,  157 => 82,  153 => 54,  151 => 48,  143 => 45,  138 => 51,  136 => 50,  133 => 67,  130 => 66,  122 => 38,  119 => 37,  116 => 35,  111 => 32,  108 => 31,  102 => 30,  98 => 21,  95 => 20,  92 => 19,  89 => 182,  85 => 25,  81 => 178,  73 => 7,  64 => 18,  60 => 24,  57 => 23,  54 => 22,  51 => 21,  48 => 20,  45 => 19,  42 => 26,  39 => 17,  36 => 16,  33 => 15,  30 => 15,);
    }
}
