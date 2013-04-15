<?php

/* ApplicationSonataNewsBundle:Post:view.html.twig */
class __TwigTemplate_188c8436b764d710fbcd205f846f7029 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("::base.html.twig");

        $this->blocks = array(
            'corps' => array($this, 'block_corps'),
            'image_accueil' => array($this, 'block_image_accueil'),
            'titre_date' => array($this, 'block_titre_date'),
            'contenu' => array($this, 'block_contenu'),
            'commentaires' => array($this, 'block_commentaires'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "::base.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 4
    public function block_corps($context, array $blocks = array())
    {
        // line 5
        echo "
    ";
        // line 6
        $this->displayBlock('image_accueil', $context, $blocks);
        // line 7
        echo "
    ";
        // line 8
        $this->displayBlock('titre_date', $context, $blocks);
        // line 21
        echo "
    ";
        // line 22
        $this->displayBlock('contenu', $context, $blocks);
        // line 37
        echo "
    ";
        // line 38
        $this->displayBlock('commentaires', $context, $blocks);
        // line 58
        echo "
";
    }

    // line 6
    public function block_image_accueil($context, array $blocks = array())
    {
    }

    // line 8
    public function block_titre_date($context, array $blocks = array())
    {
        // line 9
        echo "
        <div class=\"row blocBlanc\">
            <div class=\"six columns\">
                <div class=\"row\">

                    <h2><a href=\"";
        // line 14
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getUrl("sonata_news_view", array("permalink" => $this->env->getExtension('sonata_news')->generatePermalink($this->getContext($context, "post")))), "html", null, true);
        echo "\">";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "post"), "title"), "html", null, true);
        echo "</a></h2>
                    ";
        // line 15
        echo $this->env->getExtension('sonata_intl_datetime')->formatDate($this->getAttribute($this->getContext($context, "post"), "publicationDateStart"));
        echo "
                </div>
            </div>
        </div>

    ";
    }

    // line 22
    public function block_contenu($context, array $blocks = array())
    {
        // line 23
        echo "
        &nbsp;

        <div class=\"row blocBlanc\">
            <div class=\"six columns\">
                <div class=\"row\">
                    ";
        // line 29
        echo $this->getAttribute($this->getContext($context, "post"), "content");
        echo "
                </div>
            </div>
        </div>

        &nbsp;

    ";
    }

    // line 38
    public function block_commentaires($context, array $blocks = array())
    {
        // line 39
        echo "        <div class=\"row blocBlanc\">
            <div class=\"six columns\">
                <div class=\"row\">
        ";
        // line 42
        echo $this->env->getExtension('actions')->renderAction("SonataNewsBundle:Post:comments", array("postId" => $this->getAttribute($this->getContext($context, "post"), "id")), array());
        // line 43
        echo "
        ";
        // line 44
        if ($this->getAttribute($this->getContext($context, "post"), "iscommentable")) {
            // line 45
            echo "            ";
            echo $this->env->getExtension('actions')->renderAction("SonataNewsBundle:Post:addCommentForm", array("postId" => $this->getAttribute($this->getContext($context, "post"), "id"), "form" => $this->getContext($context, "form")), array());
            // line 49
            echo "        ";
        } else {
            // line 50
            echo "
                ";
            // line 51
            echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("message_comments_are_closed", array(), "SonataNewsBundle"), "html", null, true);
            echo "

        ";
        }
        // line 54
        echo "                        </div>
            </div>
        </div>
    ";
    }

    public function getTemplateName()
    {
        return "ApplicationSonataNewsBundle:Post:view.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  224 => 72,  388 => 154,  381 => 150,  365 => 142,  351 => 137,  346 => 136,  339 => 133,  323 => 124,  319 => 123,  315 => 122,  288 => 109,  270 => 100,  261 => 96,  239 => 86,  231 => 83,  215 => 76,  630 => 381,  628 => 377,  613 => 372,  608 => 370,  597 => 361,  593 => 360,  587 => 357,  583 => 356,  577 => 353,  542 => 330,  535 => 326,  531 => 324,  527 => 320,  463 => 284,  458 => 282,  446 => 275,  440 => 272,  428 => 265,  420 => 260,  398 => 243,  390 => 155,  342 => 134,  248 => 89,  183 => 83,  158 => 69,  295 => 135,  292 => 134,  281 => 167,  275 => 127,  237 => 110,  221 => 78,  202 => 72,  150 => 21,  160 => 23,  129 => 45,  264 => 98,  260 => 71,  226 => 81,  220 => 59,  199 => 52,  188 => 48,  177 => 42,  161 => 58,  156 => 55,  148 => 65,  124 => 43,  113 => 40,  99 => 23,  12 => 45,  638 => 211,  633 => 209,  625 => 206,  619 => 202,  606 => 198,  602 => 197,  591 => 195,  586 => 193,  579 => 191,  571 => 189,  568 => 188,  565 => 187,  545 => 103,  534 => 98,  529 => 321,  526 => 96,  514 => 85,  511 => 310,  507 => 108,  505 => 96,  499 => 84,  496 => 301,  493 => 82,  489 => 177,  469 => 168,  459 => 165,  442 => 161,  413 => 152,  410 => 253,  394 => 242,  389 => 143,  379 => 236,  370 => 229,  357 => 132,  347 => 130,  341 => 127,  301 => 137,  265 => 109,  262 => 157,  249 => 118,  217 => 136,  214 => 65,  210 => 64,  185 => 45,  182 => 82,  174 => 41,  117 => 39,  140 => 68,  97 => 91,  679 => 218,  676 => 217,  671 => 210,  665 => 207,  656 => 203,  651 => 201,  645 => 199,  643 => 389,  640 => 197,  634 => 383,  632 => 194,  629 => 193,  623 => 191,  621 => 190,  618 => 374,  612 => 187,  610 => 199,  604 => 368,  599 => 157,  595 => 196,  589 => 153,  580 => 150,  570 => 148,  567 => 346,  559 => 341,  553 => 111,  549 => 334,  546 => 109,  532 => 105,  524 => 103,  504 => 306,  501 => 94,  492 => 299,  485 => 292,  474 => 170,  471 => 85,  465 => 49,  461 => 166,  457 => 47,  448 => 44,  431 => 158,  425 => 36,  415 => 153,  411 => 31,  403 => 28,  393 => 156,  384 => 237,  382 => 184,  369 => 179,  360 => 140,  355 => 172,  352 => 171,  338 => 170,  332 => 123,  305 => 182,  299 => 161,  291 => 157,  287 => 155,  285 => 168,  278 => 142,  266 => 138,  252 => 119,  241 => 149,  235 => 130,  227 => 105,  222 => 138,  193 => 47,  179 => 63,  166 => 91,  137 => 88,  86 => 40,  335 => 94,  326 => 90,  306 => 87,  303 => 163,  283 => 107,  279 => 115,  276 => 165,  273 => 112,  271 => 163,  268 => 110,  259 => 81,  255 => 70,  245 => 115,  218 => 57,  211 => 98,  206 => 55,  190 => 69,  187 => 68,  169 => 80,  167 => 60,  164 => 34,  134 => 23,  77 => 73,  65 => 8,  56 => 28,  53 => 38,  686 => 206,  680 => 203,  677 => 202,  675 => 201,  669 => 198,  659 => 204,  654 => 202,  642 => 193,  639 => 192,  636 => 191,  627 => 207,  624 => 184,  607 => 185,  590 => 181,  585 => 179,  581 => 178,  578 => 177,  575 => 149,  572 => 175,  566 => 171,  562 => 146,  560 => 106,  555 => 167,  538 => 99,  521 => 317,  517 => 101,  512 => 99,  509 => 98,  506 => 160,  503 => 159,  500 => 158,  498 => 95,  495 => 156,  486 => 151,  482 => 172,  480 => 290,  477 => 147,  475 => 146,  472 => 287,  462 => 123,  453 => 46,  450 => 163,  437 => 138,  435 => 270,  432 => 136,  423 => 155,  421 => 35,  416 => 129,  405 => 127,  402 => 149,  400 => 248,  391 => 144,  377 => 148,  375 => 231,  371 => 145,  366 => 178,  356 => 139,  353 => 138,  343 => 128,  337 => 96,  331 => 92,  329 => 127,  324 => 119,  318 => 90,  312 => 89,  310 => 184,  302 => 116,  298 => 84,  289 => 133,  286 => 85,  274 => 77,  272 => 75,  269 => 125,  254 => 92,  250 => 67,  247 => 66,  243 => 87,  238 => 65,  236 => 63,  233 => 84,  208 => 73,  203 => 54,  200 => 61,  197 => 60,  175 => 78,  173 => 110,  112 => 51,  110 => 9,  90 => 35,  87 => 41,  69 => 54,  49 => 12,  23 => 15,  82 => 39,  62 => 30,  40 => 7,  20 => 1,  479 => 87,  473 => 161,  468 => 286,  460 => 155,  456 => 164,  452 => 151,  443 => 42,  439 => 41,  436 => 147,  434 => 159,  429 => 144,  426 => 157,  422 => 142,  412 => 134,  408 => 132,  406 => 252,  401 => 130,  397 => 129,  392 => 126,  386 => 115,  383 => 121,  380 => 112,  378 => 183,  373 => 116,  367 => 112,  364 => 177,  361 => 133,  359 => 106,  354 => 219,  340 => 97,  336 => 131,  321 => 91,  313 => 99,  311 => 166,  308 => 97,  304 => 95,  297 => 177,  293 => 158,  284 => 116,  282 => 144,  277 => 104,  267 => 99,  263 => 123,  257 => 121,  251 => 80,  246 => 76,  240 => 66,  234 => 63,  228 => 82,  223 => 58,  219 => 67,  213 => 99,  207 => 129,  198 => 123,  181 => 44,  176 => 62,  170 => 75,  168 => 60,  146 => 20,  142 => 29,  128 => 14,  125 => 51,  107 => 44,  38 => 6,  144 => 54,  141 => 19,  135 => 50,  126 => 56,  109 => 44,  103 => 45,  67 => 27,  61 => 31,  47 => 19,  105 => 9,  93 => 43,  76 => 36,  72 => 26,  68 => 9,  225 => 127,  216 => 100,  212 => 75,  205 => 54,  201 => 124,  196 => 71,  194 => 79,  191 => 119,  189 => 46,  186 => 88,  180 => 72,  172 => 16,  159 => 61,  154 => 20,  147 => 58,  132 => 49,  127 => 44,  121 => 54,  118 => 53,  114 => 38,  104 => 49,  100 => 66,  78 => 20,  75 => 14,  71 => 70,  58 => 23,  34 => 19,  27 => 16,  91 => 22,  84 => 35,  44 => 18,  25 => 14,  28 => 3,  24 => 14,  19 => 11,  94 => 23,  88 => 31,  79 => 31,  59 => 22,  31 => 4,  26 => 1,  21 => 12,  70 => 22,  63 => 23,  46 => 18,  22 => 14,  163 => 77,  155 => 75,  152 => 64,  149 => 53,  145 => 64,  139 => 61,  131 => 22,  123 => 55,  120 => 56,  115 => 75,  106 => 48,  101 => 45,  96 => 43,  83 => 15,  80 => 36,  74 => 15,  66 => 53,  55 => 58,  52 => 27,  50 => 37,  43 => 8,  41 => 11,  37 => 18,  35 => 5,  32 => 4,  29 => 18,  184 => 87,  178 => 52,  171 => 61,  165 => 58,  162 => 57,  157 => 31,  153 => 67,  151 => 42,  143 => 45,  138 => 51,  136 => 60,  133 => 58,  130 => 46,  122 => 42,  119 => 12,  116 => 11,  111 => 39,  108 => 10,  102 => 29,  98 => 47,  95 => 64,  92 => 44,  89 => 41,  85 => 38,  81 => 15,  73 => 39,  64 => 41,  60 => 6,  57 => 31,  54 => 12,  51 => 20,  48 => 22,  45 => 21,  42 => 8,  39 => 19,  36 => 16,  33 => 15,  30 => 15,);
    }
}
