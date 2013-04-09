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
        echo "
    <div class=\"step\">
        ";
        // line 9
        $this->env->loadTemplate("SensioDistributionBundle::Configurator/steps.html.twig")->display(array_merge($context, array("index" => $this->getContext($context, "index"), "count" => $this->getContext($context, "count"))));
        // line 10
        echo "
        <h1>Global Secret</h1>
        <p>Configure the global secret for your website (the secret is used for the CSRF protection among other things):</p>

        <div class=\"symfony-form-errors\">
            ";
        // line 15
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getContext($context, "form"), 'errors');
        echo "
        </div>
        <form action=\"";
        // line 17
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("_configurator_step", array("index" => $this->getContext($context, "index"))), "html", null, true);
        echo " \" method=\"POST\">
            <div class=\"symfony-form-row\">
                ";
        // line 19
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute($this->getContext($context, "form"), "secret"), 'label');
        echo "
                <div class=\"symfony-form-field\">
                    ";
        // line 21
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute($this->getContext($context, "form"), "secret"), 'widget');
        echo "
                    <a href=\"#\" onclick=\"generateSecret(); return false;\" class=\"sf-button\">
                        <span class=\"border-l\">
                            <span class=\"border-r\">
                                <span class=\"btn-bg\">Generate</span>
                            </span>
                        </span>
                    </a>
                    <div class=\"symfony-form-errors\">
                        ";
        // line 30
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute($this->getContext($context, "form"), "secret"), 'errors');
        echo "
                    </div>
                </div>
            </div>

            ";
        // line 35
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getContext($context, "form"), 'rest');
        echo "

            <div class=\"symfony-form-footer\">
                <p>
                    <button type=\"submit\" class=\"sf-button\">
                        <span class=\"border-l\">
                            <span class=\"border-r\">
                                <span class=\"btn-bg\">NEXT STEP</span>
                            </span>
                        </span>
                    </button>
                </p>
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
    </div>
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
        return array (  791 => 473,  788 => 472,  777 => 470,  773 => 469,  769 => 467,  756 => 466,  730 => 461,  727 => 460,  708 => 458,  691 => 457,  687 => 455,  683 => 454,  679 => 453,  671 => 451,  667 => 450,  663 => 449,  660 => 448,  658 => 447,  641 => 446,  615 => 440,  606 => 437,  603 => 436,  601 => 435,  532 => 396,  515 => 395,  510 => 393,  385 => 160,  382 => 159,  363 => 155,  349 => 149,  317 => 135,  311 => 131,  308 => 130,  292 => 121,  237 => 93,  225 => 87,  204 => 76,  180 => 63,  396 => 179,  378 => 170,  376 => 158,  369 => 165,  348 => 322,  334 => 145,  293 => 118,  219 => 84,  195 => 71,  344 => 119,  300 => 105,  153 => 77,  388 => 177,  346 => 321,  323 => 124,  319 => 123,  315 => 110,  212 => 79,  202 => 75,  643 => 389,  634 => 383,  630 => 445,  628 => 377,  618 => 374,  597 => 361,  593 => 360,  587 => 434,  567 => 346,  559 => 341,  535 => 326,  504 => 306,  492 => 299,  463 => 284,  458 => 282,  446 => 275,  428 => 265,  420 => 260,  390 => 155,  354 => 151,  281 => 167,  222 => 138,  217 => 136,  297 => 277,  291 => 102,  256 => 122,  251 => 101,  227 => 105,  221 => 85,  215 => 76,  210 => 97,  207 => 129,  194 => 89,  188 => 90,  161 => 63,  129 => 55,  333 => 85,  330 => 141,  325 => 82,  299 => 278,  290 => 72,  288 => 116,  270 => 110,  267 => 99,  261 => 105,  244 => 136,  239 => 86,  231 => 89,  213 => 23,  170 => 84,  159 => 53,  150 => 55,  124 => 44,  113 => 40,  12 => 45,  631 => 203,  625 => 201,  623 => 200,  617 => 196,  608 => 370,  604 => 368,  595 => 190,  591 => 189,  579 => 185,  571 => 183,  568 => 182,  565 => 181,  549 => 334,  545 => 97,  534 => 92,  529 => 321,  526 => 90,  514 => 79,  511 => 310,  505 => 391,  501 => 88,  496 => 301,  493 => 76,  489 => 171,  459 => 159,  442 => 155,  434 => 153,  431 => 152,  415 => 147,  410 => 253,  394 => 242,  332 => 116,  301 => 76,  284 => 118,  265 => 107,  262 => 104,  257 => 74,  249 => 100,  185 => 66,  174 => 59,  613 => 372,  610 => 438,  605 => 199,  598 => 191,  592 => 192,  588 => 190,  586 => 187,  583 => 356,  577 => 353,  564 => 181,  561 => 180,  553 => 177,  550 => 399,  544 => 174,  542 => 330,  539 => 172,  536 => 171,  531 => 324,  527 => 320,  507 => 102,  502 => 122,  499 => 78,  494 => 120,  491 => 119,  485 => 292,  481 => 109,  478 => 108,  470 => 105,  464 => 104,  449 => 100,  441 => 97,  436 => 96,  433 => 95,  424 => 93,  417 => 87,  411 => 86,  406 => 252,  403 => 84,  397 => 48,  389 => 137,  370 => 229,  367 => 156,  361 => 127,  357 => 152,  351 => 150,  347 => 124,  342 => 134,  339 => 146,  336 => 145,  327 => 140,  320 => 171,  316 => 80,  313 => 79,  307 => 166,  304 => 165,  296 => 74,  253 => 147,  248 => 96,  242 => 96,  234 => 140,  232 => 139,  226 => 81,  205 => 95,  198 => 132,  179 => 63,  155 => 68,  127 => 54,  335 => 94,  326 => 90,  306 => 107,  303 => 128,  279 => 115,  276 => 113,  273 => 112,  271 => 111,  259 => 103,  255 => 103,  245 => 66,  240 => 92,  218 => 83,  211 => 53,  206 => 77,  190 => 68,  181 => 115,  172 => 62,  167 => 73,  134 => 47,  65 => 17,  63 => 21,  686 => 206,  680 => 203,  677 => 202,  675 => 452,  669 => 198,  659 => 197,  654 => 195,  642 => 193,  639 => 192,  636 => 205,  627 => 185,  624 => 184,  607 => 182,  590 => 181,  585 => 179,  581 => 178,  578 => 177,  575 => 185,  572 => 184,  566 => 182,  562 => 169,  560 => 100,  555 => 178,  538 => 93,  521 => 317,  517 => 163,  512 => 394,  509 => 161,  506 => 160,  503 => 159,  500 => 389,  498 => 157,  495 => 156,  486 => 151,  482 => 166,  477 => 147,  475 => 146,  472 => 287,  468 => 286,  462 => 123,  456 => 158,  450 => 157,  443 => 140,  432 => 136,  426 => 151,  421 => 131,  416 => 129,  405 => 127,  400 => 180,  391 => 138,  386 => 176,  380 => 112,  377 => 148,  375 => 231,  371 => 145,  366 => 107,  359 => 153,  356 => 139,  353 => 138,  343 => 320,  340 => 97,  331 => 92,  329 => 127,  324 => 139,  321 => 112,  318 => 111,  310 => 184,  302 => 116,  289 => 120,  286 => 119,  282 => 69,  277 => 114,  274 => 97,  272 => 111,  269 => 75,  263 => 95,  254 => 92,  250 => 58,  243 => 93,  238 => 64,  236 => 50,  233 => 84,  228 => 88,  223 => 86,  200 => 92,  197 => 60,  191 => 69,  184 => 114,  178 => 66,  175 => 65,  146 => 64,  118 => 49,  110 => 38,  104 => 37,  90 => 37,  81 => 30,  59 => 17,  23 => 18,  76 => 28,  58 => 13,  53 => 11,  20 => 1,  152 => 67,  148 => 46,  137 => 88,  102 => 33,  100 => 36,  97 => 23,  77 => 25,  34 => 4,  480 => 290,  474 => 164,  469 => 162,  461 => 160,  457 => 153,  453 => 101,  444 => 98,  440 => 272,  437 => 138,  435 => 270,  430 => 94,  427 => 143,  423 => 149,  413 => 146,  409 => 183,  407 => 131,  402 => 143,  398 => 243,  393 => 178,  387 => 122,  384 => 237,  381 => 150,  379 => 236,  374 => 157,  368 => 112,  365 => 142,  362 => 161,  360 => 332,  355 => 157,  341 => 147,  337 => 96,  322 => 138,  314 => 99,  312 => 129,  309 => 108,  305 => 129,  298 => 125,  294 => 136,  285 => 115,  283 => 100,  278 => 98,  268 => 104,  264 => 98,  258 => 94,  252 => 59,  247 => 117,  241 => 149,  235 => 89,  229 => 87,  224 => 81,  220 => 63,  214 => 69,  208 => 73,  169 => 50,  143 => 43,  140 => 42,  132 => 59,  128 => 55,  119 => 40,  107 => 37,  71 => 13,  38 => 6,  177 => 64,  165 => 83,  160 => 23,  135 => 58,  126 => 56,  114 => 23,  84 => 27,  70 => 26,  67 => 18,  61 => 23,  94 => 21,  89 => 35,  85 => 23,  75 => 28,  68 => 20,  56 => 28,  196 => 92,  183 => 84,  171 => 58,  166 => 56,  163 => 82,  158 => 80,  156 => 58,  151 => 47,  142 => 63,  138 => 61,  136 => 71,  121 => 50,  117 => 39,  105 => 34,  91 => 35,  62 => 16,  49 => 12,  87 => 32,  21 => 2,  31 => 3,  25 => 14,  26 => 3,  28 => 3,  24 => 2,  19 => 1,  93 => 38,  88 => 28,  78 => 24,  46 => 14,  44 => 11,  27 => 7,  79 => 29,  72 => 21,  69 => 21,  47 => 10,  40 => 8,  37 => 6,  22 => 2,  246 => 70,  157 => 21,  145 => 74,  139 => 49,  131 => 45,  123 => 61,  120 => 53,  115 => 40,  111 => 47,  108 => 47,  101 => 33,  98 => 34,  96 => 30,  83 => 30,  74 => 22,  66 => 25,  55 => 12,  52 => 12,  50 => 10,  43 => 9,  41 => 7,  35 => 5,  32 => 6,  29 => 3,  209 => 78,  203 => 128,  199 => 93,  193 => 69,  189 => 71,  187 => 67,  182 => 87,  176 => 86,  173 => 85,  168 => 57,  164 => 59,  162 => 54,  154 => 60,  149 => 53,  147 => 75,  144 => 92,  141 => 73,  133 => 47,  130 => 39,  125 => 51,  122 => 41,  116 => 57,  112 => 39,  109 => 52,  106 => 51,  103 => 25,  99 => 31,  95 => 39,  92 => 31,  86 => 40,  82 => 25,  80 => 29,  73 => 23,  64 => 19,  60 => 20,  57 => 39,  54 => 15,  51 => 37,  48 => 16,  45 => 9,  42 => 12,  39 => 10,  36 => 8,  33 => 9,  30 => 5,);
    }
}
