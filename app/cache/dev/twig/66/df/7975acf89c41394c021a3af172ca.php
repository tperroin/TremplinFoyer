<?php

/* ApplicationSonataNewsBundle:Post:archive.html.twig */
class __TwigTemplate_66df7975acf89c41394c021a3af172ca extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("::base.html.twig");

        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'annonce' => array($this, 'block_annonce'),
            'corps' => array($this, 'block_corps'),
            'titre_date' => array($this, 'block_titre_date'),
            'resume' => array($this, 'block_resume'),
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

    // line 3
    public function block_title($context, array $blocks = array())
    {
        echo "Blog ";
    }

    // line 4
    public function block_annonce($context, array $blocks = array())
    {
    }

    // line 6
    public function block_corps($context, array $blocks = array())
    {
        // line 7
        echo "    ";
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getContext($context, "pager"), "getResults", array(), "method"));
        $context['_iterated'] = false;
        $context['loop'] = array(
          'parent' => $context['_parent'],
          'index0' => 0,
          'index'  => 1,
          'first'  => true,
        );
        if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof Countable)) {
            $length = count($context['_seq']);
            $context['loop']['revindex0'] = $length - 1;
            $context['loop']['revindex'] = $length;
            $context['loop']['length'] = $length;
            $context['loop']['last'] = 1 === $length;
        }
        foreach ($context['_seq'] as $context["_key"] => $context["post"]) {
            // line 8
            echo "        
        ";
            // line 9
            $this->displayBlock('titre_date', $context, $blocks);
            // line 22
            echo "        ";
            $this->displayBlock('resume', $context, $blocks);
            // line 39
            echo "  
    ";
            $context['_iterated'] = true;
            ++$context['loop']['index0'];
            ++$context['loop']['index'];
            $context['loop']['first'] = false;
            if (isset($context['loop']['length'])) {
                --$context['loop']['revindex0'];
                --$context['loop']['revindex'];
                $context['loop']['last'] = 0 === $context['loop']['revindex0'];
            }
        }
        if (!$context['_iterated']) {
            // line 41
            echo "            <div class=\"row\">
                <div class=\"large-6 columns\">
                    <div class=\"row\">
        ";
            // line 44
            echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("no_post_found", array(), "SonataNewsBundle"), "html", null, true);
            echo "
                            </div>
                </div>
            </div>
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['post'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
    }

    // line 9
    public function block_titre_date($context, array $blocks = array())
    {
        // line 10
        echo "    <div class=\"row blocBlanc\">
                <div class=\"large-6 columns\">
            <div class=\"row \">
                <div class=\"large-12 columns\">
                    <div class=\"row\">
                        
                        <h2><a href=\"";
        // line 16
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getUrl("sonata_news_view", array("permalink" => $this->env->getExtension('sonata_news')->generatePermalink($this->getContext($context, "post")))), "html", null, true);
        echo "\">";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "post"), "title"), "html", null, true);
        echo "</a></h2>
                        ";
        // line 17
        echo $this->env->getExtension('sonata_intl_datetime')->formatDate($this->getAttribute($this->getContext($context, "post"), "publicationDateStart"));
        echo "
                    </div>
                </div>
            </div>
        ";
    }

    // line 22
    public function block_resume($context, array $blocks = array())
    {
        // line 23
        echo "    
            &nbsp;
            
            <div class=\"row \">
                <div class=\"large-12 columns\">
                    <div class=\"row\">
                        ";
        // line 29
        echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "post"), "abstract"), "html", null, true);
        echo "
                    </div>
                </div>
            </div>
                    </div>
        </div>
            
            &nbsp;
            
        ";
    }

    public function getTemplateName()
    {
        return "ApplicationSonataNewsBundle:Post:archive.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  388 => 154,  381 => 150,  365 => 142,  351 => 137,  346 => 136,  339 => 133,  323 => 124,  319 => 123,  315 => 122,  288 => 109,  270 => 100,  261 => 96,  239 => 86,  231 => 83,  215 => 76,  630 => 381,  628 => 377,  613 => 372,  608 => 370,  597 => 361,  593 => 360,  587 => 357,  583 => 356,  577 => 353,  542 => 330,  535 => 326,  531 => 324,  527 => 320,  463 => 284,  458 => 282,  446 => 275,  440 => 272,  428 => 265,  420 => 260,  398 => 243,  390 => 155,  342 => 134,  248 => 89,  183 => 83,  158 => 69,  295 => 135,  292 => 134,  281 => 167,  275 => 127,  237 => 110,  221 => 78,  202 => 72,  150 => 66,  160 => 23,  129 => 55,  264 => 98,  260 => 71,  226 => 81,  220 => 59,  199 => 52,  188 => 48,  177 => 42,  161 => 58,  156 => 55,  148 => 65,  124 => 44,  113 => 40,  99 => 23,  12 => 45,  638 => 211,  633 => 209,  625 => 206,  619 => 202,  606 => 198,  602 => 197,  591 => 195,  586 => 193,  579 => 191,  571 => 189,  568 => 188,  565 => 187,  545 => 103,  534 => 98,  529 => 321,  526 => 96,  514 => 85,  511 => 310,  507 => 108,  505 => 96,  499 => 84,  496 => 301,  493 => 82,  489 => 177,  469 => 168,  459 => 165,  442 => 161,  413 => 152,  410 => 253,  394 => 242,  389 => 143,  379 => 236,  370 => 229,  357 => 132,  347 => 130,  341 => 127,  301 => 137,  265 => 109,  262 => 157,  249 => 118,  217 => 136,  214 => 65,  210 => 64,  185 => 67,  182 => 82,  174 => 77,  117 => 47,  140 => 68,  97 => 44,  679 => 218,  676 => 217,  671 => 210,  665 => 207,  656 => 203,  651 => 201,  645 => 199,  643 => 389,  640 => 197,  634 => 383,  632 => 194,  629 => 193,  623 => 191,  621 => 190,  618 => 374,  612 => 187,  610 => 199,  604 => 368,  599 => 157,  595 => 196,  589 => 153,  580 => 150,  570 => 148,  567 => 346,  559 => 341,  553 => 111,  549 => 334,  546 => 109,  532 => 105,  524 => 103,  504 => 306,  501 => 94,  492 => 299,  485 => 292,  474 => 170,  471 => 85,  465 => 49,  461 => 166,  457 => 47,  448 => 44,  431 => 158,  425 => 36,  415 => 153,  411 => 31,  403 => 28,  393 => 156,  384 => 237,  382 => 184,  369 => 179,  360 => 140,  355 => 172,  352 => 171,  338 => 170,  332 => 123,  305 => 182,  299 => 161,  291 => 157,  287 => 155,  285 => 168,  278 => 142,  266 => 138,  252 => 119,  241 => 149,  235 => 130,  227 => 105,  222 => 138,  193 => 50,  179 => 63,  166 => 91,  137 => 88,  86 => 40,  335 => 94,  326 => 90,  306 => 87,  303 => 163,  283 => 107,  279 => 115,  276 => 165,  273 => 112,  271 => 163,  268 => 110,  259 => 81,  255 => 70,  245 => 115,  218 => 57,  211 => 98,  206 => 51,  190 => 69,  187 => 68,  169 => 80,  167 => 60,  164 => 72,  134 => 23,  77 => 36,  65 => 8,  56 => 28,  53 => 23,  686 => 206,  680 => 203,  677 => 202,  675 => 201,  669 => 198,  659 => 204,  654 => 202,  642 => 193,  639 => 192,  636 => 191,  627 => 207,  624 => 184,  607 => 185,  590 => 181,  585 => 179,  581 => 178,  578 => 177,  575 => 149,  572 => 175,  566 => 171,  562 => 146,  560 => 106,  555 => 167,  538 => 99,  521 => 317,  517 => 101,  512 => 99,  509 => 98,  506 => 160,  503 => 159,  500 => 158,  498 => 95,  495 => 156,  486 => 151,  482 => 172,  480 => 290,  477 => 147,  475 => 146,  472 => 287,  462 => 123,  453 => 46,  450 => 163,  437 => 138,  435 => 270,  432 => 136,  423 => 155,  421 => 35,  416 => 129,  405 => 127,  402 => 149,  400 => 248,  391 => 144,  377 => 148,  375 => 231,  371 => 145,  366 => 178,  356 => 139,  353 => 138,  343 => 128,  337 => 96,  331 => 92,  329 => 127,  324 => 119,  318 => 90,  312 => 89,  310 => 184,  302 => 116,  298 => 84,  289 => 133,  286 => 85,  274 => 77,  272 => 75,  269 => 125,  254 => 92,  250 => 67,  247 => 66,  243 => 87,  238 => 65,  236 => 63,  233 => 84,  208 => 73,  203 => 128,  200 => 61,  197 => 60,  175 => 78,  173 => 110,  112 => 51,  110 => 51,  90 => 35,  87 => 41,  69 => 30,  49 => 12,  23 => 15,  82 => 39,  62 => 30,  40 => 20,  20 => 1,  479 => 87,  473 => 161,  468 => 286,  460 => 155,  456 => 164,  452 => 151,  443 => 42,  439 => 41,  436 => 147,  434 => 159,  429 => 144,  426 => 157,  422 => 142,  412 => 134,  408 => 132,  406 => 252,  401 => 130,  397 => 129,  392 => 126,  386 => 115,  383 => 121,  380 => 112,  378 => 183,  373 => 116,  367 => 112,  364 => 177,  361 => 133,  359 => 106,  354 => 219,  340 => 97,  336 => 131,  321 => 91,  313 => 99,  311 => 166,  308 => 97,  304 => 95,  297 => 177,  293 => 158,  284 => 116,  282 => 144,  277 => 104,  267 => 99,  263 => 123,  257 => 121,  251 => 80,  246 => 76,  240 => 66,  234 => 63,  228 => 82,  223 => 58,  219 => 67,  213 => 99,  207 => 129,  198 => 123,  181 => 115,  176 => 62,  170 => 75,  168 => 60,  146 => 64,  142 => 29,  128 => 56,  125 => 51,  107 => 44,  38 => 4,  144 => 92,  141 => 61,  135 => 47,  126 => 56,  109 => 44,  103 => 45,  67 => 27,  61 => 31,  47 => 24,  105 => 9,  93 => 43,  76 => 36,  72 => 35,  68 => 9,  225 => 127,  216 => 100,  212 => 75,  205 => 54,  201 => 124,  196 => 71,  194 => 79,  191 => 119,  189 => 77,  186 => 88,  180 => 72,  172 => 16,  159 => 61,  154 => 20,  147 => 58,  132 => 59,  127 => 54,  121 => 54,  118 => 53,  114 => 46,  104 => 49,  100 => 66,  78 => 20,  75 => 19,  71 => 30,  58 => 23,  34 => 19,  27 => 16,  91 => 41,  84 => 35,  44 => 24,  25 => 14,  28 => 3,  24 => 14,  19 => 13,  94 => 43,  88 => 31,  79 => 31,  59 => 23,  31 => 4,  26 => 2,  21 => 12,  70 => 22,  63 => 14,  46 => 7,  22 => 14,  163 => 77,  155 => 75,  152 => 64,  149 => 53,  145 => 64,  139 => 61,  131 => 22,  123 => 55,  120 => 56,  115 => 75,  106 => 48,  101 => 45,  96 => 43,  83 => 15,  80 => 36,  74 => 15,  66 => 31,  55 => 26,  52 => 27,  50 => 11,  43 => 6,  41 => 17,  37 => 18,  35 => 5,  32 => 3,  29 => 18,  184 => 87,  178 => 52,  171 => 61,  165 => 58,  162 => 57,  157 => 21,  153 => 67,  151 => 42,  143 => 45,  138 => 57,  136 => 60,  133 => 58,  130 => 46,  122 => 17,  119 => 42,  116 => 16,  111 => 39,  108 => 10,  102 => 47,  98 => 47,  95 => 64,  92 => 44,  89 => 41,  85 => 38,  81 => 37,  73 => 39,  64 => 26,  60 => 25,  57 => 34,  54 => 12,  51 => 25,  48 => 21,  45 => 9,  42 => 8,  39 => 19,  36 => 19,  33 => 15,  30 => 15,);
    }
}
