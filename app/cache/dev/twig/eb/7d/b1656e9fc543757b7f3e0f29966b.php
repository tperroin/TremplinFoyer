<?php

/* SonataBlockBundle:Block:block_core_action.html.twig */
class __TwigTemplate_eb7db1656e9fc543757b7f3e0f29966b extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->blocks = array(
            'block' => array($this, 'block_block'),
        );
    }

    protected function doGetParent(array $context)
    {
        return $this->env->resolveTemplate($this->getAttribute($this->getAttribute($this->getContext($context, "sonata_block"), "templates"), "block_base"));
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->getParent($context)->display($context, array_merge($this->blocks, $blocks));
    }

    // line 14
    public function block_block($context, array $blocks = array())
    {
        // line 15
        echo "    ";
        echo $this->getContext($context, "content");
        echo "
";
    }

    public function getTemplateName()
    {
        return "SonataBlockBundle:Block:block_core_action.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  160 => 66,  129 => 55,  264 => 72,  260 => 71,  226 => 61,  220 => 59,  199 => 52,  188 => 48,  177 => 42,  161 => 46,  156 => 65,  148 => 63,  124 => 47,  113 => 40,  99 => 23,  12 => 45,  638 => 211,  633 => 209,  610 => 199,  602 => 197,  586 => 193,  579 => 191,  568 => 188,  565 => 187,  529 => 97,  526 => 96,  514 => 85,  511 => 84,  507 => 108,  501 => 94,  499 => 84,  496 => 83,  493 => 82,  489 => 177,  474 => 170,  469 => 168,  459 => 165,  442 => 161,  413 => 152,  410 => 151,  394 => 145,  389 => 143,  379 => 137,  370 => 134,  357 => 132,  347 => 130,  341 => 127,  301 => 117,  265 => 109,  262 => 82,  249 => 77,  217 => 58,  214 => 65,  210 => 64,  185 => 47,  182 => 46,  174 => 51,  117 => 47,  140 => 56,  97 => 39,  672 => 216,  667 => 209,  661 => 206,  655 => 203,  652 => 202,  650 => 201,  647 => 200,  641 => 198,  630 => 194,  628 => 193,  625 => 206,  619 => 202,  617 => 189,  614 => 188,  608 => 186,  606 => 198,  603 => 184,  600 => 183,  595 => 196,  591 => 195,  576 => 149,  571 => 189,  563 => 146,  558 => 145,  549 => 104,  545 => 103,  542 => 108,  534 => 98,  528 => 104,  520 => 102,  513 => 100,  508 => 98,  505 => 96,  497 => 95,  494 => 94,  488 => 93,  481 => 87,  470 => 85,  467 => 84,  461 => 166,  457 => 47,  448 => 44,  431 => 158,  425 => 36,  415 => 153,  411 => 31,  403 => 28,  393 => 221,  384 => 140,  382 => 183,  369 => 178,  360 => 174,  355 => 171,  352 => 170,  338 => 169,  332 => 123,  305 => 163,  299 => 160,  291 => 156,  287 => 154,  285 => 144,  278 => 141,  266 => 137,  252 => 69,  241 => 73,  235 => 129,  227 => 127,  222 => 68,  193 => 50,  179 => 111,  166 => 90,  137 => 60,  86 => 33,  335 => 94,  326 => 90,  306 => 87,  303 => 162,  283 => 84,  279 => 115,  276 => 81,  273 => 112,  271 => 79,  268 => 110,  259 => 81,  255 => 70,  245 => 67,  218 => 57,  211 => 56,  206 => 51,  190 => 49,  187 => 49,  169 => 50,  167 => 79,  164 => 67,  134 => 37,  77 => 28,  65 => 24,  56 => 26,  53 => 21,  686 => 206,  680 => 203,  677 => 202,  675 => 217,  669 => 198,  659 => 197,  654 => 195,  642 => 193,  639 => 197,  636 => 196,  627 => 207,  624 => 184,  607 => 182,  590 => 181,  585 => 152,  581 => 178,  578 => 177,  575 => 176,  572 => 175,  566 => 147,  562 => 169,  560 => 106,  555 => 144,  538 => 99,  521 => 164,  517 => 101,  512 => 162,  509 => 161,  506 => 160,  503 => 159,  500 => 96,  498 => 157,  495 => 156,  486 => 151,  482 => 172,  480 => 148,  477 => 147,  475 => 86,  472 => 169,  462 => 123,  453 => 46,  450 => 163,  437 => 138,  435 => 137,  432 => 136,  423 => 155,  421 => 35,  416 => 129,  405 => 127,  402 => 149,  400 => 148,  391 => 144,  377 => 111,  375 => 181,  371 => 109,  366 => 177,  356 => 105,  353 => 131,  343 => 128,  337 => 96,  331 => 92,  329 => 122,  324 => 119,  318 => 90,  312 => 89,  310 => 118,  302 => 86,  298 => 84,  289 => 81,  286 => 85,  274 => 77,  272 => 75,  269 => 74,  254 => 68,  250 => 67,  247 => 66,  243 => 65,  238 => 65,  236 => 63,  233 => 62,  208 => 55,  203 => 62,  200 => 61,  197 => 60,  175 => 45,  173 => 70,  112 => 47,  110 => 29,  90 => 39,  87 => 17,  69 => 26,  49 => 24,  23 => 11,  82 => 30,  62 => 27,  40 => 24,  20 => 1,  479 => 162,  473 => 161,  468 => 125,  460 => 155,  456 => 164,  452 => 151,  443 => 42,  439 => 41,  436 => 147,  434 => 159,  429 => 144,  426 => 157,  422 => 142,  412 => 134,  408 => 132,  406 => 29,  401 => 130,  397 => 129,  392 => 126,  386 => 115,  383 => 121,  380 => 112,  378 => 182,  373 => 116,  367 => 112,  364 => 176,  361 => 133,  359 => 106,  354 => 106,  340 => 97,  336 => 103,  321 => 91,  313 => 99,  311 => 165,  308 => 97,  304 => 95,  297 => 159,  293 => 157,  284 => 116,  282 => 143,  277 => 114,  267 => 85,  263 => 71,  257 => 80,  251 => 80,  246 => 76,  240 => 66,  234 => 63,  228 => 62,  223 => 58,  219 => 67,  213 => 69,  207 => 63,  198 => 122,  181 => 47,  176 => 110,  170 => 61,  168 => 60,  146 => 46,  142 => 56,  128 => 35,  125 => 51,  107 => 44,  38 => 6,  144 => 62,  141 => 61,  135 => 47,  126 => 51,  109 => 44,  103 => 45,  67 => 31,  61 => 18,  47 => 17,  105 => 26,  93 => 43,  76 => 28,  72 => 19,  68 => 22,  225 => 126,  216 => 90,  212 => 88,  205 => 54,  201 => 123,  196 => 121,  194 => 79,  191 => 56,  189 => 77,  186 => 76,  180 => 72,  172 => 16,  159 => 61,  154 => 59,  147 => 58,  132 => 48,  127 => 54,  121 => 52,  118 => 51,  114 => 46,  104 => 42,  100 => 40,  78 => 35,  75 => 32,  71 => 30,  58 => 12,  34 => 4,  27 => 14,  91 => 38,  84 => 19,  44 => 16,  25 => 12,  28 => 14,  24 => 13,  19 => 11,  94 => 38,  88 => 31,  79 => 16,  59 => 23,  31 => 3,  26 => 14,  21 => 2,  70 => 32,  63 => 28,  46 => 19,  22 => 12,  163 => 77,  155 => 81,  152 => 64,  149 => 47,  145 => 62,  139 => 61,  131 => 55,  123 => 50,  120 => 21,  115 => 50,  106 => 44,  101 => 44,  96 => 42,  83 => 15,  80 => 29,  74 => 15,  66 => 28,  55 => 25,  52 => 24,  50 => 25,  43 => 21,  41 => 19,  37 => 17,  35 => 13,  32 => 16,  29 => 15,  184 => 114,  178 => 52,  171 => 93,  165 => 58,  162 => 57,  157 => 82,  153 => 43,  151 => 42,  143 => 45,  138 => 57,  136 => 50,  133 => 57,  130 => 66,  122 => 50,  119 => 49,  116 => 35,  111 => 45,  108 => 44,  102 => 41,  98 => 21,  95 => 19,  92 => 39,  89 => 22,  85 => 37,  81 => 178,  73 => 27,  64 => 13,  60 => 15,  57 => 27,  54 => 14,  51 => 19,  48 => 15,  45 => 8,  42 => 7,  39 => 16,  36 => 17,  33 => 16,  30 => 15,);
    }
}
