<?php

/* SensioDistributionBundle:Configurator/Step:doctrine.html.twig */
class __TwigTemplate_76c49ef531a70e167cc78407bbc2be56 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("SensioDistributionBundle::Configurator/layout.html.twig");

        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "SensioDistributionBundle::Configurator/layout.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_title($context, array $blocks = array())
    {
        echo "Symfony - Configure database";
    }

    // line 5
    public function block_content($context, array $blocks = array())
    {
        // line 6
        echo "    ";
        $this->env->getExtension('form')->renderer->setTheme($this->getContext($context, "form"), array(0 => "SensioDistributionBundle::Configurator/form.html.twig"));
        // line 7
        echo "    ";
        $this->env->loadTemplate("SensioDistributionBundle::Configurator/steps.html.twig")->display(array_merge($context, array("index" => $this->getContext($context, "index"), "count" => $this->getContext($context, "count"))));
        // line 8
        echo "
    <h1>Configure your Database</h1>
    <p>If your website needs a database connection, please configure it here.</p>

    ";
        // line 12
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getContext($context, "form"), 'errors');
        echo "
    <form action=\"";
        // line 13
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("_configurator_step", array("index" => $this->getContext($context, "index"))), "html", null, true);
        echo "\" method=\"POST\">
        <div class=\"symfony-form-column\">
            ";
        // line 15
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute($this->getContext($context, "form"), "driver"), 'row');
        echo "
            ";
        // line 16
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute($this->getContext($context, "form"), "host"), 'row');
        echo "
            ";
        // line 17
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute($this->getContext($context, "form"), "name"), 'row');
        echo "
        </div>
        <div class=\"symfony-form-column\">
            ";
        // line 20
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute($this->getContext($context, "form"), "user"), 'row');
        echo "
            ";
        // line 21
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute($this->getContext($context, "form"), "password"), 'row');
        echo "
        </div>

        ";
        // line 24
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getContext($context, "form"), 'rest');
        echo "

        <div class=\"symfony-form-footer\">
            <p><input type=\"submit\" value=\"Next Step\" class=\"symfony-button-grey\" /></p>
            <p>* mandatory fields</p>
        </div>
    </form>
";
    }

    public function getTemplateName()
    {
        return "SensioDistributionBundle:Configurator/Step:doctrine.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  786 => 466,  783 => 465,  772 => 463,  768 => 462,  764 => 460,  751 => 459,  725 => 454,  722 => 453,  703 => 451,  682 => 448,  678 => 447,  674 => 446,  670 => 445,  666 => 444,  662 => 443,  657 => 441,  614 => 434,  609 => 432,  605 => 431,  588 => 429,  556 => 399,  518 => 394,  516 => 393,  385 => 159,  362 => 153,  344 => 147,  330 => 140,  327 => 139,  320 => 135,  314 => 131,  280 => 114,  258 => 103,  333 => 141,  325 => 138,  316 => 145,  309 => 141,  204 => 71,  209 => 77,  242 => 54,  232 => 47,  229 => 96,  244 => 84,  192 => 72,  224 => 45,  388 => 160,  381 => 150,  365 => 142,  351 => 137,  346 => 136,  339 => 145,  323 => 149,  319 => 123,  315 => 122,  288 => 129,  270 => 100,  261 => 96,  239 => 82,  231 => 88,  215 => 79,  630 => 381,  628 => 377,  613 => 372,  608 => 370,  597 => 361,  593 => 360,  587 => 357,  583 => 356,  577 => 353,  542 => 330,  535 => 326,  531 => 324,  527 => 320,  463 => 284,  458 => 282,  446 => 275,  440 => 272,  428 => 265,  420 => 260,  398 => 243,  390 => 155,  342 => 146,  248 => 89,  183 => 63,  158 => 56,  295 => 121,  292 => 120,  281 => 125,  275 => 111,  237 => 110,  221 => 83,  202 => 92,  150 => 21,  160 => 59,  129 => 64,  264 => 105,  260 => 235,  226 => 86,  220 => 44,  199 => 52,  188 => 87,  177 => 59,  161 => 79,  156 => 67,  148 => 73,  124 => 59,  113 => 40,  99 => 43,  12 => 45,  638 => 211,  633 => 209,  625 => 206,  619 => 202,  606 => 198,  602 => 430,  591 => 195,  586 => 193,  579 => 191,  571 => 189,  568 => 188,  565 => 187,  545 => 103,  534 => 98,  529 => 321,  526 => 96,  514 => 85,  511 => 391,  507 => 108,  505 => 96,  499 => 84,  496 => 301,  493 => 82,  489 => 177,  469 => 168,  459 => 165,  442 => 161,  413 => 152,  410 => 253,  394 => 242,  389 => 143,  379 => 158,  370 => 156,  357 => 151,  347 => 160,  341 => 127,  301 => 125,  265 => 109,  262 => 236,  249 => 118,  217 => 83,  214 => 82,  210 => 64,  185 => 68,  182 => 86,  174 => 58,  117 => 55,  140 => 42,  97 => 23,  679 => 218,  676 => 217,  671 => 210,  665 => 207,  656 => 203,  651 => 201,  645 => 199,  643 => 389,  640 => 440,  634 => 383,  632 => 194,  629 => 439,  623 => 191,  621 => 190,  618 => 374,  612 => 187,  610 => 199,  604 => 368,  599 => 157,  595 => 196,  589 => 153,  580 => 150,  570 => 148,  567 => 346,  559 => 341,  553 => 111,  549 => 334,  546 => 109,  532 => 105,  524 => 103,  504 => 306,  501 => 94,  492 => 299,  485 => 292,  474 => 170,  471 => 85,  465 => 49,  461 => 166,  457 => 47,  448 => 44,  431 => 158,  425 => 36,  415 => 153,  411 => 31,  403 => 28,  393 => 156,  384 => 237,  382 => 184,  369 => 179,  360 => 152,  355 => 172,  352 => 149,  338 => 170,  332 => 123,  305 => 182,  299 => 161,  291 => 157,  287 => 118,  285 => 168,  278 => 142,  266 => 138,  252 => 100,  241 => 101,  235 => 130,  227 => 105,  222 => 138,  193 => 68,  179 => 85,  166 => 79,  137 => 69,  86 => 29,  335 => 157,  326 => 90,  306 => 128,  303 => 163,  283 => 107,  279 => 115,  276 => 248,  273 => 110,  271 => 96,  268 => 107,  259 => 109,  255 => 70,  245 => 96,  218 => 91,  211 => 81,  206 => 78,  190 => 67,  187 => 68,  169 => 82,  167 => 64,  164 => 80,  134 => 54,  77 => 21,  65 => 17,  56 => 39,  53 => 38,  686 => 450,  680 => 203,  677 => 202,  675 => 201,  669 => 198,  659 => 442,  654 => 202,  642 => 193,  639 => 192,  636 => 191,  627 => 207,  624 => 184,  607 => 185,  590 => 181,  585 => 179,  581 => 178,  578 => 177,  575 => 149,  572 => 175,  566 => 171,  562 => 146,  560 => 106,  555 => 167,  538 => 396,  521 => 395,  517 => 101,  512 => 99,  509 => 98,  506 => 389,  503 => 159,  500 => 158,  498 => 95,  495 => 156,  486 => 151,  482 => 172,  480 => 290,  477 => 147,  475 => 146,  472 => 287,  462 => 123,  453 => 46,  450 => 163,  437 => 138,  435 => 270,  432 => 136,  423 => 155,  421 => 35,  416 => 129,  405 => 127,  402 => 149,  400 => 248,  391 => 144,  377 => 157,  375 => 231,  371 => 145,  366 => 155,  356 => 163,  353 => 138,  343 => 159,  337 => 96,  331 => 92,  329 => 127,  324 => 119,  318 => 90,  312 => 89,  310 => 184,  302 => 137,  298 => 84,  289 => 119,  286 => 85,  274 => 121,  272 => 75,  269 => 125,  254 => 101,  250 => 136,  247 => 97,  243 => 87,  238 => 218,  236 => 63,  233 => 98,  208 => 70,  203 => 77,  200 => 61,  197 => 68,  175 => 60,  173 => 71,  112 => 39,  110 => 38,  90 => 28,  87 => 33,  69 => 15,  49 => 15,  23 => 3,  82 => 26,  62 => 16,  40 => 8,  20 => 1,  479 => 87,  473 => 161,  468 => 286,  460 => 155,  456 => 164,  452 => 151,  443 => 42,  439 => 41,  436 => 147,  434 => 159,  429 => 144,  426 => 157,  422 => 142,  412 => 134,  408 => 132,  406 => 252,  401 => 130,  397 => 129,  392 => 126,  386 => 115,  383 => 121,  380 => 112,  378 => 183,  373 => 116,  367 => 112,  364 => 177,  361 => 133,  359 => 106,  354 => 150,  340 => 158,  336 => 131,  321 => 91,  313 => 99,  311 => 130,  308 => 129,  304 => 95,  297 => 177,  293 => 158,  284 => 116,  282 => 115,  277 => 104,  267 => 99,  263 => 73,  257 => 234,  251 => 105,  246 => 76,  240 => 219,  234 => 89,  228 => 87,  223 => 58,  219 => 67,  213 => 41,  207 => 76,  198 => 74,  181 => 44,  176 => 84,  170 => 60,  168 => 80,  146 => 72,  142 => 71,  128 => 45,  125 => 44,  107 => 37,  38 => 6,  144 => 54,  141 => 70,  135 => 47,  126 => 63,  109 => 44,  103 => 25,  67 => 17,  61 => 18,  47 => 15,  105 => 99,  93 => 41,  76 => 17,  72 => 21,  68 => 20,  225 => 88,  216 => 72,  212 => 78,  205 => 93,  201 => 69,  196 => 69,  194 => 90,  191 => 70,  189 => 21,  186 => 88,  180 => 19,  172 => 64,  159 => 78,  154 => 75,  147 => 58,  132 => 47,  127 => 52,  121 => 61,  118 => 103,  114 => 106,  104 => 37,  100 => 36,  78 => 24,  75 => 24,  71 => 23,  58 => 14,  34 => 5,  27 => 5,  91 => 28,  84 => 23,  44 => 8,  25 => 3,  28 => 3,  24 => 2,  19 => 1,  94 => 30,  88 => 30,  79 => 18,  59 => 15,  31 => 6,  26 => 3,  21 => 1,  70 => 13,  63 => 16,  46 => 12,  22 => 2,  163 => 13,  155 => 11,  152 => 74,  149 => 9,  145 => 57,  139 => 57,  131 => 46,  123 => 35,  120 => 50,  115 => 40,  106 => 48,  101 => 33,  96 => 35,  83 => 24,  80 => 32,  74 => 22,  66 => 28,  55 => 15,  52 => 18,  50 => 12,  43 => 12,  41 => 7,  37 => 6,  35 => 5,  32 => 4,  29 => 3,  184 => 70,  178 => 62,  171 => 83,  165 => 54,  162 => 53,  157 => 17,  153 => 62,  151 => 47,  143 => 43,  138 => 55,  136 => 125,  133 => 54,  130 => 39,  122 => 51,  119 => 43,  116 => 31,  111 => 39,  108 => 41,  102 => 34,  98 => 32,  95 => 30,  92 => 31,  89 => 28,  85 => 38,  81 => 24,  73 => 20,  64 => 11,  60 => 20,  57 => 20,  54 => 13,  51 => 16,  48 => 20,  45 => 11,  42 => 11,  39 => 10,  36 => 9,  33 => 10,  30 => 7,);
    }
}
