<?php

/* SonataIntlBundle:CRUD:show_datetime.html.twig */
class __TwigTemplate_b328fd7b7511b7183beb7a198e993773 extends Twig_Template
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
        if (twig_test_empty($this->getContext($context, "value"))) {
            // line 16
            echo "&nbsp;";
        } else {
            // line 18
            echo $this->env->getExtension('sonata_intl_datetime')->formatDatetime($this->getContext($context, "value"));
        }
    }

    public function getTemplateName()
    {
        return "SonataIntlBundle:CRUD:show_datetime.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  229 => 96,  244 => 84,  192 => 66,  388 => 154,  381 => 150,  365 => 142,  351 => 137,  346 => 136,  339 => 133,  323 => 124,  319 => 123,  315 => 122,  288 => 109,  270 => 100,  261 => 96,  239 => 82,  231 => 79,  215 => 76,  643 => 389,  634 => 383,  618 => 374,  613 => 372,  604 => 368,  597 => 361,  593 => 360,  587 => 357,  583 => 356,  577 => 353,  567 => 346,  559 => 341,  535 => 326,  531 => 324,  527 => 320,  504 => 306,  492 => 299,  485 => 292,  463 => 284,  458 => 282,  446 => 275,  440 => 272,  428 => 265,  420 => 260,  398 => 243,  390 => 155,  342 => 134,  248 => 89,  183 => 48,  158 => 64,  295 => 135,  292 => 134,  281 => 167,  275 => 127,  237 => 110,  221 => 78,  202 => 65,  150 => 66,  160 => 18,  129 => 51,  264 => 98,  260 => 71,  226 => 81,  220 => 59,  199 => 52,  188 => 48,  177 => 72,  161 => 56,  156 => 67,  148 => 65,  124 => 51,  113 => 40,  99 => 38,  12 => 45,  638 => 211,  633 => 209,  610 => 199,  602 => 197,  586 => 193,  579 => 191,  568 => 188,  565 => 187,  529 => 321,  526 => 96,  514 => 85,  511 => 310,  507 => 108,  501 => 94,  499 => 84,  496 => 301,  493 => 82,  489 => 177,  474 => 170,  469 => 168,  459 => 165,  442 => 161,  413 => 152,  410 => 253,  394 => 242,  389 => 143,  379 => 236,  370 => 229,  357 => 132,  347 => 130,  341 => 127,  301 => 137,  265 => 109,  262 => 157,  249 => 118,  217 => 136,  214 => 65,  210 => 64,  185 => 64,  182 => 82,  174 => 23,  117 => 39,  140 => 58,  97 => 44,  672 => 216,  667 => 209,  661 => 206,  655 => 203,  652 => 202,  650 => 201,  647 => 200,  641 => 198,  630 => 381,  628 => 377,  625 => 206,  619 => 202,  617 => 189,  614 => 188,  608 => 370,  606 => 198,  603 => 184,  600 => 183,  595 => 196,  591 => 195,  576 => 149,  571 => 189,  563 => 146,  558 => 145,  549 => 334,  545 => 103,  542 => 330,  534 => 98,  528 => 104,  520 => 102,  513 => 100,  508 => 98,  505 => 96,  497 => 95,  494 => 94,  488 => 93,  481 => 87,  470 => 85,  467 => 84,  461 => 166,  457 => 47,  448 => 44,  431 => 158,  425 => 36,  415 => 153,  411 => 31,  403 => 28,  393 => 156,  384 => 237,  382 => 183,  369 => 178,  360 => 140,  355 => 171,  352 => 170,  338 => 169,  332 => 123,  305 => 182,  299 => 160,  291 => 156,  287 => 154,  285 => 168,  278 => 141,  266 => 137,  252 => 119,  241 => 101,  235 => 129,  227 => 105,  222 => 138,  193 => 50,  179 => 61,  166 => 90,  137 => 56,  86 => 31,  335 => 94,  326 => 90,  306 => 87,  303 => 162,  283 => 107,  279 => 115,  276 => 165,  273 => 112,  271 => 163,  268 => 110,  259 => 81,  255 => 70,  245 => 115,  218 => 91,  211 => 98,  206 => 51,  190 => 69,  187 => 68,  169 => 80,  167 => 71,  164 => 19,  134 => 55,  77 => 28,  65 => 24,  56 => 25,  53 => 23,  686 => 206,  680 => 203,  677 => 202,  675 => 217,  669 => 198,  659 => 197,  654 => 195,  642 => 193,  639 => 197,  636 => 196,  627 => 207,  624 => 184,  607 => 182,  590 => 181,  585 => 152,  581 => 178,  578 => 177,  575 => 176,  572 => 175,  566 => 147,  562 => 169,  560 => 106,  555 => 144,  538 => 99,  521 => 317,  517 => 101,  512 => 162,  509 => 161,  506 => 160,  503 => 159,  500 => 96,  498 => 157,  495 => 156,  486 => 151,  482 => 172,  480 => 290,  477 => 147,  475 => 86,  472 => 287,  462 => 123,  453 => 46,  450 => 163,  437 => 138,  435 => 270,  432 => 136,  423 => 155,  421 => 35,  416 => 129,  405 => 127,  402 => 149,  400 => 248,  391 => 144,  377 => 148,  375 => 231,  371 => 145,  366 => 177,  356 => 139,  353 => 138,  343 => 128,  337 => 96,  331 => 92,  329 => 127,  324 => 119,  318 => 90,  312 => 89,  310 => 184,  302 => 116,  298 => 84,  289 => 133,  286 => 85,  274 => 77,  272 => 75,  269 => 125,  254 => 92,  250 => 67,  247 => 66,  243 => 87,  238 => 65,  236 => 63,  233 => 98,  208 => 70,  203 => 128,  200 => 61,  197 => 68,  175 => 60,  173 => 71,  112 => 42,  110 => 46,  90 => 38,  87 => 34,  69 => 30,  49 => 21,  23 => 13,  82 => 39,  62 => 25,  40 => 21,  20 => 11,  479 => 162,  473 => 161,  468 => 286,  460 => 155,  456 => 164,  452 => 151,  443 => 42,  439 => 41,  436 => 147,  434 => 159,  429 => 144,  426 => 157,  422 => 142,  412 => 134,  408 => 132,  406 => 252,  401 => 130,  397 => 129,  392 => 126,  386 => 115,  383 => 121,  380 => 112,  378 => 182,  373 => 116,  367 => 112,  364 => 176,  361 => 133,  359 => 106,  354 => 219,  340 => 97,  336 => 131,  321 => 91,  313 => 99,  311 => 165,  308 => 97,  304 => 95,  297 => 177,  293 => 157,  284 => 116,  282 => 143,  277 => 104,  267 => 99,  263 => 123,  257 => 121,  251 => 105,  246 => 76,  240 => 66,  234 => 63,  228 => 82,  223 => 58,  219 => 67,  213 => 99,  207 => 129,  198 => 80,  181 => 115,  176 => 62,  170 => 22,  168 => 69,  146 => 49,  142 => 19,  128 => 44,  125 => 51,  107 => 40,  38 => 15,  144 => 54,  141 => 58,  135 => 55,  126 => 21,  109 => 44,  103 => 39,  67 => 35,  61 => 31,  47 => 20,  105 => 26,  93 => 37,  76 => 36,  72 => 28,  68 => 25,  225 => 95,  216 => 72,  212 => 88,  205 => 84,  201 => 69,  196 => 71,  194 => 79,  191 => 119,  189 => 77,  186 => 88,  180 => 72,  172 => 16,  159 => 61,  154 => 66,  147 => 61,  132 => 108,  127 => 44,  121 => 54,  118 => 103,  114 => 102,  104 => 43,  100 => 94,  78 => 31,  75 => 30,  71 => 26,  58 => 23,  34 => 14,  27 => 14,  91 => 32,  84 => 35,  44 => 18,  25 => 14,  28 => 14,  24 => 14,  19 => 11,  94 => 36,  88 => 64,  79 => 33,  59 => 22,  31 => 15,  26 => 2,  21 => 12,  70 => 33,  63 => 23,  46 => 20,  22 => 12,  163 => 66,  155 => 51,  152 => 50,  149 => 53,  145 => 60,  139 => 57,  131 => 55,  123 => 43,  120 => 43,  115 => 75,  106 => 48,  101 => 45,  96 => 35,  83 => 34,  80 => 34,  74 => 31,  66 => 31,  55 => 21,  52 => 24,  50 => 22,  43 => 8,  41 => 19,  37 => 6,  35 => 17,  32 => 15,  29 => 14,  184 => 74,  178 => 52,  171 => 61,  165 => 58,  162 => 69,  157 => 17,  153 => 63,  151 => 33,  143 => 59,  138 => 47,  136 => 109,  133 => 54,  130 => 54,  122 => 104,  119 => 42,  116 => 47,  111 => 39,  108 => 99,  102 => 37,  98 => 47,  95 => 64,  92 => 43,  89 => 32,  85 => 31,  81 => 32,  73 => 32,  64 => 26,  60 => 26,  57 => 25,  54 => 22,  51 => 20,  48 => 23,  45 => 19,  42 => 18,  39 => 18,  36 => 18,  33 => 16,  30 => 16,);
    }
}
