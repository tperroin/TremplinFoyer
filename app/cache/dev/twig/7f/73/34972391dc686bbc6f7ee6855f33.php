<?php

/* SensioDistributionBundle:Configurator/Step:secret.html.twig */
class __TwigTemplate_7f7334972391dc686bbc6f7ee6855f33 extends Twig_Template
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
        echo "Symfony - Configure global Secret";
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
    <h1>Global Secret</h1>
    <p>Configure the global secret for your website (the secret is used for the CSRF protection among other things):</p>

    ";
        // line 12
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getContext($context, "form"), 'errors');
        echo "
    <form action=\"";
        // line 13
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("_configurator_step", array("index" => $this->getContext($context, "index"))), "html", null, true);
        echo " \" method=\"POST\">
        <div class=\"symfony-form-row\">
            ";
        // line 15
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute($this->getContext($context, "form"), "secret"), 'label');
        echo "
            <div class=\"symfony-form-field\">
                ";
        // line 17
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute($this->getContext($context, "form"), "secret"), 'widget');
        echo "
                <a class=\"symfony-button-grey\" href=\"#\" onclick=\"generateSecret(); return false;\">Generate</a>
                <div class=\"symfony-form-errors\">
                    ";
        // line 20
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute($this->getContext($context, "form"), "secret"), 'errors');
        echo "
                </div>
            </div>
        </div>

        ";
        // line 25
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getContext($context, "form"), 'rest');
        echo "

        <div class=\"symfony-form-footer\">
            <p><input type=\"submit\" value=\"Next Step\" class=\"symfony-button-grey\" /></p>
            <p>* mandatory fields</p>
        </div>

    </form>

    <script type=\"text/javascript\">
        function generateSecret()
        {
            var result = '';
            for (i=0; i < 32; i++) {
                result += Math.round(Math.random()*16).toString(16);
            }
            document.getElementById('distributionbundle_secret_step_secret').value = result;
        }
    </script>
";
    }

    public function getTemplateName()
    {
        return "SensioDistributionBundle:Configurator/Step:secret.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  786 => 466,  783 => 465,  772 => 463,  768 => 462,  764 => 460,  751 => 459,  725 => 454,  722 => 453,  703 => 451,  682 => 448,  678 => 447,  674 => 446,  670 => 445,  666 => 444,  662 => 443,  657 => 441,  640 => 440,  629 => 439,  609 => 432,  605 => 431,  588 => 429,  556 => 399,  518 => 394,  516 => 393,  385 => 159,  362 => 153,  344 => 147,  330 => 140,  327 => 139,  320 => 135,  314 => 131,  280 => 114,  258 => 103,  333 => 141,  325 => 138,  316 => 145,  309 => 141,  192 => 72,  204 => 71,  209 => 77,  388 => 160,  381 => 150,  365 => 142,  351 => 137,  346 => 136,  339 => 145,  323 => 149,  319 => 123,  315 => 122,  288 => 129,  270 => 100,  261 => 96,  239 => 86,  231 => 88,  215 => 79,  643 => 389,  634 => 383,  618 => 374,  613 => 372,  604 => 368,  597 => 361,  593 => 360,  587 => 357,  583 => 356,  577 => 353,  567 => 346,  559 => 341,  535 => 326,  531 => 324,  527 => 320,  504 => 306,  492 => 299,  485 => 292,  463 => 284,  458 => 282,  446 => 275,  440 => 272,  428 => 265,  420 => 260,  398 => 243,  390 => 155,  342 => 146,  248 => 89,  183 => 63,  158 => 56,  295 => 121,  292 => 120,  281 => 125,  275 => 111,  237 => 110,  221 => 83,  202 => 92,  150 => 66,  129 => 55,  264 => 105,  260 => 235,  226 => 86,  220 => 59,  199 => 52,  188 => 87,  177 => 59,  161 => 79,  124 => 59,  113 => 40,  99 => 43,  12 => 45,  638 => 211,  633 => 209,  610 => 199,  602 => 430,  586 => 193,  579 => 191,  568 => 188,  565 => 187,  529 => 321,  526 => 96,  514 => 85,  511 => 391,  507 => 108,  501 => 94,  499 => 84,  496 => 301,  493 => 82,  489 => 177,  474 => 170,  469 => 168,  459 => 165,  442 => 161,  413 => 152,  410 => 253,  394 => 242,  389 => 143,  379 => 158,  370 => 156,  357 => 151,  347 => 160,  341 => 127,  301 => 125,  265 => 109,  262 => 236,  249 => 118,  217 => 83,  214 => 82,  210 => 64,  185 => 68,  182 => 86,  174 => 58,  140 => 42,  672 => 216,  667 => 209,  661 => 206,  655 => 203,  652 => 202,  650 => 201,  647 => 200,  641 => 198,  630 => 381,  628 => 377,  625 => 206,  619 => 202,  617 => 189,  614 => 434,  608 => 370,  606 => 198,  603 => 184,  600 => 183,  595 => 196,  591 => 195,  576 => 149,  571 => 189,  563 => 146,  558 => 145,  549 => 334,  545 => 103,  542 => 330,  534 => 98,  528 => 104,  520 => 102,  513 => 100,  508 => 98,  505 => 96,  497 => 95,  494 => 94,  488 => 93,  481 => 87,  470 => 85,  467 => 84,  461 => 166,  457 => 47,  448 => 44,  431 => 158,  425 => 36,  415 => 153,  411 => 31,  403 => 28,  393 => 156,  384 => 237,  382 => 183,  369 => 178,  360 => 152,  355 => 171,  352 => 149,  338 => 169,  332 => 123,  305 => 182,  299 => 160,  291 => 156,  287 => 118,  285 => 168,  278 => 141,  266 => 137,  252 => 100,  241 => 149,  235 => 129,  227 => 105,  222 => 138,  193 => 68,  179 => 85,  166 => 90,  335 => 157,  326 => 90,  306 => 128,  303 => 162,  283 => 107,  279 => 115,  276 => 248,  273 => 110,  271 => 163,  268 => 107,  259 => 109,  255 => 70,  245 => 96,  218 => 57,  211 => 81,  206 => 78,  190 => 67,  187 => 68,  169 => 82,  167 => 64,  134 => 54,  65 => 17,  56 => 39,  686 => 450,  680 => 203,  677 => 202,  675 => 217,  669 => 198,  659 => 442,  654 => 195,  642 => 193,  639 => 197,  636 => 196,  627 => 207,  624 => 184,  607 => 182,  590 => 181,  585 => 152,  581 => 178,  578 => 177,  575 => 176,  572 => 175,  566 => 147,  562 => 169,  560 => 106,  555 => 144,  538 => 396,  521 => 395,  517 => 101,  512 => 162,  509 => 161,  506 => 389,  503 => 159,  500 => 96,  498 => 157,  495 => 156,  486 => 151,  482 => 172,  480 => 290,  477 => 147,  475 => 86,  472 => 287,  462 => 123,  453 => 46,  450 => 163,  437 => 138,  435 => 270,  432 => 136,  423 => 155,  421 => 35,  416 => 129,  405 => 127,  402 => 149,  400 => 248,  391 => 144,  377 => 157,  375 => 231,  371 => 145,  366 => 155,  356 => 163,  353 => 138,  343 => 159,  337 => 96,  331 => 92,  329 => 127,  324 => 119,  318 => 90,  312 => 89,  310 => 184,  302 => 137,  298 => 84,  289 => 119,  286 => 85,  274 => 121,  272 => 75,  269 => 125,  254 => 101,  250 => 136,  247 => 97,  243 => 87,  238 => 218,  236 => 63,  233 => 84,  208 => 73,  203 => 77,  200 => 61,  197 => 60,  175 => 78,  112 => 39,  110 => 38,  90 => 28,  87 => 33,  69 => 15,  49 => 15,  23 => 3,  82 => 26,  62 => 16,  53 => 38,  40 => 8,  20 => 1,  173 => 110,  164 => 80,  160 => 59,  156 => 55,  148 => 73,  137 => 69,  117 => 55,  97 => 23,  86 => 29,  77 => 21,  479 => 162,  473 => 161,  468 => 286,  460 => 155,  456 => 164,  452 => 151,  443 => 42,  439 => 41,  436 => 147,  434 => 159,  429 => 144,  426 => 157,  422 => 142,  412 => 134,  408 => 132,  406 => 252,  401 => 130,  397 => 129,  392 => 126,  386 => 115,  383 => 121,  380 => 112,  378 => 182,  373 => 116,  367 => 112,  364 => 176,  361 => 133,  359 => 106,  354 => 150,  340 => 158,  336 => 131,  321 => 91,  313 => 99,  311 => 130,  308 => 129,  304 => 95,  297 => 177,  293 => 157,  284 => 116,  282 => 115,  277 => 104,  267 => 99,  263 => 123,  257 => 234,  251 => 80,  246 => 76,  240 => 219,  234 => 89,  228 => 87,  223 => 58,  219 => 67,  213 => 99,  207 => 76,  198 => 74,  181 => 115,  176 => 84,  170 => 60,  168 => 60,  146 => 72,  142 => 71,  128 => 45,  125 => 44,  107 => 37,  38 => 6,  144 => 92,  141 => 61,  135 => 47,  126 => 56,  109 => 47,  103 => 25,  67 => 17,  61 => 18,  47 => 15,  105 => 26,  93 => 41,  76 => 17,  72 => 21,  68 => 20,  225 => 88,  216 => 100,  212 => 78,  205 => 93,  201 => 123,  196 => 69,  194 => 90,  191 => 70,  189 => 77,  186 => 88,  180 => 72,  172 => 64,  159 => 78,  154 => 75,  147 => 58,  132 => 47,  127 => 52,  121 => 54,  118 => 53,  114 => 46,  104 => 37,  100 => 36,  78 => 25,  75 => 24,  71 => 23,  58 => 14,  34 => 5,  27 => 5,  91 => 28,  84 => 23,  44 => 8,  25 => 14,  28 => 3,  24 => 2,  19 => 1,  94 => 43,  88 => 30,  79 => 18,  59 => 15,  31 => 6,  26 => 3,  21 => 1,  70 => 20,  63 => 16,  46 => 12,  22 => 2,  163 => 77,  155 => 75,  152 => 35,  149 => 53,  145 => 57,  139 => 61,  131 => 46,  123 => 35,  120 => 50,  115 => 40,  106 => 48,  101 => 33,  96 => 35,  83 => 24,  80 => 32,  74 => 22,  66 => 28,  55 => 15,  52 => 18,  50 => 12,  43 => 12,  41 => 7,  37 => 6,  35 => 5,  32 => 4,  29 => 3,  184 => 70,  178 => 62,  171 => 83,  165 => 54,  162 => 53,  157 => 21,  153 => 62,  151 => 47,  143 => 43,  138 => 55,  136 => 60,  133 => 58,  130 => 39,  122 => 51,  119 => 43,  116 => 31,  111 => 39,  108 => 41,  102 => 34,  98 => 32,  95 => 30,  92 => 31,  89 => 28,  85 => 38,  81 => 24,  73 => 20,  64 => 17,  60 => 20,  57 => 20,  54 => 13,  51 => 16,  48 => 20,  45 => 11,  42 => 11,  39 => 10,  36 => 9,  33 => 10,  30 => 7,);
    }
}
