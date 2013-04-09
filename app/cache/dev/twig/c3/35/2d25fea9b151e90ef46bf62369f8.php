<?php

/* ::base.html.twig */
class __TwigTemplate_c3352d25fea9b151e90ef46bf62369f8 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'stylesheets' => array($this, 'block_stylesheets'),
            'body' => array($this, 'block_body'),
            'javascripts' => array($this, 'block_javascripts'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html>
<html>
    <head>
        <meta charset=\"UTF-8\" />
        <title>";
        // line 5
        $this->displayBlock('title', $context, $blocks);
        echo "</title>
        ";
        // line 6
        $this->displayBlock('stylesheets', $context, $blocks);
        // line 7
        echo "        <link rel=\"icon\" type=\"image/x-icon\" href=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("favicon.ico"), "html", null, true);
        echo "\" />
    </head>
    <body>
        ";
        // line 10
        $this->displayBlock('body', $context, $blocks);
        // line 11
        echo "        ";
        $this->displayBlock('javascripts', $context, $blocks);
        // line 12
        echo "    </body>
</html>
";
    }

    // line 5
    public function block_title($context, array $blocks = array())
    {
        echo "Welcome!";
    }

    // line 6
    public function block_stylesheets($context, array $blocks = array())
    {
    }

    // line 10
    public function block_body($context, array $blocks = array())
    {
    }

    // line 11
    public function block_javascripts($context, array $blocks = array())
    {
    }

    public function getTemplateName()
    {
        return "::base.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  1176 => 331,  1170 => 330,  1164 => 329,  1158 => 328,  1152 => 327,  1146 => 326,  1140 => 325,  1134 => 324,  1128 => 323,  1112 => 317,  1105 => 316,  1103 => 315,  1100 => 314,  1077 => 310,  1052 => 309,  1050 => 308,  1047 => 307,  1035 => 302,  1030 => 301,  1028 => 300,  1025 => 299,  1016 => 293,  1010 => 291,  1007 => 290,  1002 => 289,  1000 => 288,  997 => 287,  990 => 282,  983 => 280,  980 => 276,  976 => 275,  973 => 274,  970 => 273,  968 => 272,  965 => 271,  957 => 267,  955 => 266,  952 => 265,  945 => 260,  942 => 259,  934 => 254,  930 => 253,  926 => 252,  923 => 251,  921 => 250,  918 => 249,  910 => 245,  908 => 241,  906 => 240,  903 => 239,  882 => 233,  879 => 232,  876 => 231,  873 => 230,  870 => 229,  867 => 228,  864 => 227,  861 => 226,  858 => 225,  855 => 224,  853 => 223,  850 => 222,  842 => 216,  839 => 215,  837 => 214,  834 => 213,  826 => 209,  823 => 208,  821 => 207,  818 => 206,  810 => 202,  807 => 201,  805 => 200,  802 => 199,  794 => 195,  791 => 194,  789 => 193,  778 => 188,  775 => 187,  773 => 186,  770 => 185,  762 => 181,  759 => 180,  757 => 179,  754 => 178,  746 => 174,  744 => 173,  741 => 172,  733 => 168,  730 => 167,  728 => 166,  717 => 161,  714 => 160,  712 => 159,  710 => 158,  707 => 157,  700 => 152,  692 => 151,  687 => 150,  681 => 148,  676 => 146,  673 => 145,  665 => 139,  663 => 138,  660 => 135,  649 => 132,  646 => 131,  644 => 130,  632 => 123,  620 => 120,  615 => 119,  601 => 114,  580 => 108,  564 => 104,  530 => 96,  523 => 93,  490 => 87,  478 => 81,  438 => 69,  407 => 59,  387 => 50,  350 => 35,  345 => 33,  334 => 27,  290 => 14,  256 => 329,  244 => 323,  195 => 249,  786 => 192,  783 => 465,  772 => 463,  768 => 462,  764 => 460,  751 => 459,  725 => 165,  722 => 453,  703 => 451,  682 => 448,  678 => 147,  674 => 446,  670 => 445,  666 => 444,  662 => 137,  657 => 441,  640 => 440,  629 => 439,  609 => 117,  605 => 431,  588 => 429,  556 => 399,  518 => 394,  516 => 91,  385 => 159,  362 => 153,  344 => 147,  330 => 140,  327 => 139,  320 => 135,  314 => 21,  280 => 114,  258 => 330,  333 => 141,  325 => 138,  316 => 22,  309 => 141,  192 => 248,  204 => 71,  209 => 77,  388 => 160,  381 => 150,  365 => 142,  351 => 137,  346 => 136,  339 => 145,  323 => 24,  319 => 123,  315 => 122,  288 => 129,  270 => 100,  261 => 96,  239 => 86,  231 => 307,  215 => 286,  643 => 389,  634 => 383,  618 => 374,  613 => 372,  604 => 115,  597 => 361,  593 => 360,  587 => 357,  583 => 109,  577 => 353,  567 => 346,  559 => 102,  535 => 326,  531 => 324,  527 => 320,  504 => 306,  492 => 299,  485 => 292,  463 => 284,  458 => 282,  446 => 275,  440 => 70,  428 => 265,  420 => 260,  398 => 243,  390 => 155,  342 => 32,  248 => 325,  183 => 63,  158 => 56,  295 => 16,  292 => 15,  281 => 125,  275 => 111,  237 => 110,  221 => 83,  202 => 262,  150 => 66,  129 => 145,  264 => 3,  260 => 331,  226 => 299,  220 => 296,  199 => 52,  188 => 87,  177 => 59,  161 => 198,  124 => 129,  113 => 40,  99 => 68,  12 => 45,  638 => 211,  633 => 209,  610 => 199,  602 => 430,  586 => 193,  579 => 191,  568 => 188,  565 => 187,  529 => 321,  526 => 96,  514 => 85,  511 => 391,  507 => 108,  501 => 94,  499 => 84,  496 => 301,  493 => 88,  489 => 177,  474 => 170,  469 => 78,  459 => 75,  442 => 161,  413 => 152,  410 => 253,  394 => 242,  389 => 143,  379 => 47,  370 => 156,  357 => 151,  347 => 34,  341 => 127,  301 => 125,  265 => 109,  262 => 236,  249 => 118,  217 => 83,  214 => 82,  210 => 270,  185 => 68,  182 => 222,  174 => 213,  140 => 42,  672 => 216,  667 => 209,  661 => 136,  655 => 134,  652 => 202,  650 => 201,  647 => 200,  641 => 129,  630 => 381,  628 => 122,  625 => 206,  619 => 202,  617 => 189,  614 => 434,  608 => 370,  606 => 116,  603 => 184,  600 => 183,  595 => 196,  591 => 195,  576 => 149,  571 => 189,  563 => 146,  558 => 145,  549 => 334,  545 => 103,  542 => 98,  534 => 98,  528 => 104,  520 => 102,  513 => 90,  508 => 98,  505 => 96,  497 => 95,  494 => 94,  488 => 93,  481 => 82,  470 => 85,  467 => 77,  461 => 166,  457 => 47,  448 => 71,  431 => 158,  425 => 36,  415 => 153,  411 => 31,  403 => 28,  393 => 52,  384 => 49,  382 => 48,  369 => 43,  360 => 152,  355 => 171,  352 => 149,  338 => 169,  332 => 123,  305 => 182,  299 => 160,  291 => 156,  287 => 13,  285 => 168,  278 => 8,  266 => 137,  252 => 327,  241 => 322,  235 => 129,  227 => 105,  222 => 138,  193 => 68,  179 => 221,  166 => 205,  335 => 157,  326 => 90,  306 => 128,  303 => 162,  283 => 107,  279 => 115,  276 => 248,  273 => 110,  271 => 163,  268 => 107,  259 => 109,  255 => 70,  245 => 96,  218 => 287,  211 => 81,  206 => 78,  190 => 239,  187 => 238,  169 => 206,  167 => 64,  134 => 157,  65 => 17,  56 => 12,  686 => 450,  680 => 203,  677 => 202,  675 => 217,  669 => 198,  659 => 442,  654 => 195,  642 => 193,  639 => 197,  636 => 196,  627 => 207,  624 => 121,  607 => 182,  590 => 181,  585 => 110,  581 => 178,  578 => 177,  575 => 176,  572 => 175,  566 => 147,  562 => 103,  560 => 106,  555 => 144,  538 => 396,  521 => 92,  517 => 101,  512 => 162,  509 => 161,  506 => 389,  503 => 159,  500 => 96,  498 => 157,  495 => 89,  486 => 151,  482 => 172,  480 => 290,  477 => 147,  475 => 80,  472 => 287,  462 => 76,  453 => 46,  450 => 72,  437 => 138,  435 => 68,  432 => 136,  423 => 155,  421 => 62,  416 => 61,  405 => 58,  402 => 57,  400 => 248,  391 => 144,  377 => 157,  375 => 231,  371 => 145,  366 => 155,  356 => 37,  353 => 36,  343 => 159,  337 => 96,  331 => 92,  329 => 26,  324 => 119,  318 => 90,  312 => 89,  310 => 184,  302 => 137,  298 => 84,  289 => 119,  286 => 85,  274 => 121,  272 => 6,  269 => 5,  254 => 328,  250 => 326,  247 => 97,  243 => 87,  238 => 320,  236 => 314,  233 => 313,  208 => 265,  203 => 77,  200 => 259,  197 => 258,  175 => 78,  112 => 39,  110 => 38,  90 => 27,  87 => 26,  69 => 11,  49 => 15,  23 => 1,  82 => 26,  62 => 14,  53 => 5,  40 => 8,  20 => 1,  173 => 110,  164 => 199,  160 => 59,  156 => 191,  148 => 73,  137 => 69,  117 => 55,  97 => 23,  86 => 46,  77 => 21,  479 => 162,  473 => 161,  468 => 286,  460 => 155,  456 => 74,  452 => 151,  443 => 42,  439 => 41,  436 => 147,  434 => 159,  429 => 64,  426 => 157,  422 => 142,  412 => 60,  408 => 132,  406 => 252,  401 => 130,  397 => 129,  392 => 126,  386 => 115,  383 => 121,  380 => 112,  378 => 182,  373 => 116,  367 => 42,  364 => 41,  361 => 133,  359 => 106,  354 => 150,  340 => 158,  336 => 131,  321 => 23,  313 => 99,  311 => 20,  308 => 129,  304 => 95,  297 => 177,  293 => 157,  284 => 116,  282 => 115,  277 => 104,  267 => 4,  263 => 123,  257 => 234,  251 => 80,  246 => 324,  240 => 219,  234 => 89,  228 => 306,  223 => 298,  219 => 67,  213 => 271,  207 => 76,  198 => 74,  181 => 115,  176 => 219,  170 => 60,  168 => 60,  146 => 177,  142 => 71,  128 => 45,  125 => 44,  107 => 37,  38 => 5,  144 => 172,  141 => 171,  135 => 47,  126 => 144,  109 => 102,  103 => 25,  67 => 16,  61 => 2,  47 => 12,  105 => 26,  93 => 28,  76 => 31,  72 => 18,  68 => 20,  225 => 88,  216 => 100,  212 => 78,  205 => 264,  201 => 123,  196 => 69,  194 => 90,  191 => 70,  189 => 77,  186 => 88,  180 => 72,  172 => 64,  159 => 192,  154 => 185,  147 => 58,  132 => 47,  127 => 52,  121 => 128,  118 => 53,  114 => 108,  104 => 87,  100 => 36,  78 => 25,  75 => 24,  71 => 19,  58 => 14,  34 => 5,  27 => 5,  91 => 56,  84 => 25,  44 => 11,  25 => 14,  28 => 3,  24 => 2,  19 => 1,  94 => 57,  88 => 30,  79 => 23,  59 => 6,  31 => 5,  26 => 3,  21 => 1,  70 => 20,  63 => 16,  46 => 10,  22 => 2,  163 => 77,  155 => 75,  152 => 35,  149 => 178,  145 => 57,  139 => 165,  131 => 156,  123 => 35,  120 => 50,  115 => 40,  106 => 101,  101 => 86,  96 => 67,  83 => 24,  80 => 32,  74 => 21,  66 => 12,  55 => 13,  52 => 13,  50 => 12,  43 => 7,  41 => 6,  37 => 7,  35 => 7,  32 => 4,  29 => 5,  184 => 236,  178 => 62,  171 => 212,  165 => 54,  162 => 53,  157 => 21,  153 => 62,  151 => 184,  143 => 43,  138 => 55,  136 => 164,  133 => 58,  130 => 39,  122 => 51,  119 => 114,  116 => 113,  111 => 107,  108 => 33,  102 => 30,  98 => 29,  95 => 30,  92 => 31,  89 => 47,  85 => 38,  81 => 24,  73 => 20,  64 => 10,  60 => 20,  57 => 14,  54 => 13,  51 => 11,  48 => 11,  45 => 8,  42 => 10,  39 => 10,  36 => 5,  33 => 6,  30 => 3,);
    }
}
