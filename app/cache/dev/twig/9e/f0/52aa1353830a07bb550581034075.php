<?php

/* SonataDoctrineORMAdminBundle:CRUD:list_orm_many_to_many.html.twig */
class __TwigTemplate_9ef052aa1353830a07bb550581034075 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->blocks = array(
            'field' => array($this, 'block_field'),
        );
    }

    protected function doGetParent(array $context)
    {
        return $this->env->resolveTemplate($this->getAttribute($this->getContext($context, "admin"), "getTemplate", array(0 => "base_list_field"), "method"));
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->getParent($context)->display($context, array_merge($this->blocks, $blocks));
    }

    // line 14
    public function block_field($context, array $blocks = array())
    {
        // line 15
        echo "    ";
        if ((($this->getAttribute($this->getContext($context, "field_description"), "hasassociationadmin") && $this->getAttribute($this->getAttribute($this->getContext($context, "field_description"), "associationadmin"), "hasRoute", array(0 => "edit"), "method")) && $this->getAttribute($this->getAttribute($this->getContext($context, "field_description"), "associationadmin"), "isGranted", array(0 => "edit"), "method"))) {
            // line 16
            echo "        ";
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getContext($context, "value"));
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
            foreach ($context['_seq'] as $context["_key"] => $context["element"]) {
                // line 17
                echo "            <a href=\"";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getContext($context, "field_description"), "associationadmin"), "generateObjectUrl", array(0 => "edit", 1 => $this->getContext($context, "element")), "method"), "html", null, true);
                echo "\">";
                echo twig_escape_filter($this->env, $this->env->getExtension('sonata_admin')->renderRelationElement($this->getContext($context, "element"), $this->getContext($context, "field_description")), "html", null, true);
                echo "</a>";
                if ((!$this->getAttribute($this->getContext($context, "loop"), "last"))) {
                    echo ", ";
                }
                // line 18
                echo "        ";
                ++$context['loop']['index0'];
                ++$context['loop']['index'];
                $context['loop']['first'] = false;
                if (isset($context['loop']['length'])) {
                    --$context['loop']['revindex0'];
                    --$context['loop']['revindex'];
                    $context['loop']['last'] = 0 === $context['loop']['revindex0'];
                }
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['element'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 19
            echo "    ";
        } else {
            // line 20
            echo "        ";
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getContext($context, "value"));
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
            foreach ($context['_seq'] as $context["_key"] => $context["element"]) {
                // line 21
                echo "            ";
                echo twig_escape_filter($this->env, $this->env->getExtension('sonata_admin')->renderRelationElement($this->getContext($context, "element"), $this->getContext($context, "field_description")), "html", null, true);
                if ((!$this->getAttribute($this->getContext($context, "loop"), "last"))) {
                    echo ", ";
                }
                // line 22
                echo "        ";
                ++$context['loop']['index0'];
                ++$context['loop']['index'];
                $context['loop']['first'] = false;
                if (isset($context['loop']['length'])) {
                    --$context['loop']['revindex0'];
                    --$context['loop']['revindex'];
                    $context['loop']['last'] = 0 === $context['loop']['revindex0'];
                }
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['element'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 23
            echo "    ";
        }
    }

    public function getTemplateName()
    {
        return "SonataDoctrineORMAdminBundle:CRUD:list_orm_many_to_many.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  153 => 67,  388 => 154,  346 => 136,  323 => 124,  319 => 123,  315 => 122,  212 => 75,  202 => 72,  643 => 389,  634 => 383,  630 => 381,  628 => 377,  618 => 374,  597 => 361,  593 => 360,  587 => 357,  567 => 346,  559 => 341,  535 => 326,  504 => 306,  492 => 299,  463 => 284,  458 => 282,  446 => 275,  428 => 265,  420 => 260,  390 => 155,  354 => 219,  281 => 167,  222 => 138,  217 => 136,  297 => 177,  291 => 135,  256 => 122,  251 => 120,  227 => 105,  221 => 78,  215 => 76,  210 => 97,  207 => 129,  194 => 89,  188 => 86,  161 => 70,  129 => 55,  333 => 85,  330 => 84,  325 => 82,  299 => 75,  290 => 72,  288 => 109,  270 => 100,  267 => 99,  261 => 96,  244 => 56,  239 => 86,  231 => 83,  213 => 23,  170 => 89,  159 => 54,  150 => 93,  124 => 44,  113 => 40,  12 => 45,  631 => 203,  625 => 201,  623 => 200,  617 => 196,  608 => 370,  604 => 368,  595 => 190,  591 => 189,  579 => 185,  571 => 183,  568 => 182,  565 => 181,  549 => 334,  545 => 97,  534 => 92,  529 => 321,  526 => 90,  514 => 79,  511 => 310,  505 => 90,  501 => 88,  496 => 301,  493 => 76,  489 => 171,  459 => 159,  442 => 155,  434 => 153,  431 => 152,  415 => 147,  410 => 253,  394 => 242,  332 => 117,  301 => 76,  284 => 110,  265 => 125,  262 => 157,  257 => 74,  249 => 71,  185 => 83,  174 => 51,  613 => 372,  610 => 204,  605 => 199,  598 => 191,  592 => 192,  588 => 190,  586 => 187,  583 => 356,  577 => 353,  564 => 181,  561 => 180,  553 => 177,  550 => 176,  544 => 174,  542 => 330,  539 => 172,  536 => 171,  531 => 324,  527 => 320,  507 => 102,  502 => 122,  499 => 78,  494 => 120,  491 => 119,  485 => 292,  481 => 109,  478 => 108,  470 => 105,  464 => 104,  449 => 100,  441 => 97,  436 => 96,  433 => 95,  424 => 93,  417 => 87,  411 => 86,  406 => 252,  403 => 84,  397 => 48,  389 => 137,  370 => 229,  367 => 39,  361 => 127,  357 => 126,  351 => 137,  347 => 124,  342 => 134,  339 => 133,  336 => 131,  327 => 204,  320 => 171,  316 => 80,  313 => 79,  307 => 166,  304 => 165,  296 => 74,  253 => 147,  248 => 89,  242 => 142,  234 => 140,  232 => 139,  226 => 81,  205 => 95,  198 => 132,  179 => 63,  155 => 68,  127 => 54,  335 => 94,  326 => 90,  306 => 77,  303 => 139,  279 => 68,  276 => 165,  273 => 66,  271 => 163,  259 => 123,  255 => 60,  245 => 66,  240 => 64,  218 => 57,  211 => 53,  206 => 21,  190 => 69,  181 => 115,  172 => 76,  167 => 73,  134 => 42,  65 => 30,  63 => 24,  686 => 206,  680 => 203,  677 => 202,  675 => 201,  669 => 198,  659 => 197,  654 => 195,  642 => 193,  639 => 192,  636 => 205,  627 => 185,  624 => 184,  607 => 182,  590 => 181,  585 => 179,  581 => 178,  578 => 177,  575 => 185,  572 => 184,  566 => 182,  562 => 169,  560 => 100,  555 => 178,  538 => 93,  521 => 317,  517 => 163,  512 => 124,  509 => 161,  506 => 160,  503 => 159,  500 => 158,  498 => 157,  495 => 156,  486 => 151,  482 => 166,  477 => 147,  475 => 146,  472 => 287,  468 => 286,  462 => 123,  456 => 158,  450 => 157,  443 => 140,  432 => 136,  426 => 151,  421 => 131,  416 => 129,  405 => 127,  400 => 248,  391 => 138,  386 => 115,  380 => 112,  377 => 148,  375 => 231,  371 => 145,  366 => 107,  359 => 106,  356 => 139,  353 => 138,  343 => 122,  340 => 97,  331 => 92,  329 => 127,  324 => 113,  321 => 81,  318 => 90,  310 => 184,  302 => 116,  289 => 156,  286 => 85,  282 => 69,  277 => 104,  274 => 77,  272 => 76,  269 => 75,  263 => 71,  254 => 92,  250 => 58,  243 => 87,  238 => 64,  236 => 50,  233 => 84,  228 => 82,  223 => 64,  200 => 92,  197 => 60,  191 => 119,  184 => 114,  178 => 83,  175 => 16,  146 => 46,  118 => 52,  110 => 51,  104 => 49,  90 => 35,  81 => 172,  59 => 18,  23 => 18,  76 => 20,  58 => 23,  53 => 21,  20 => 1,  152 => 67,  148 => 63,  137 => 88,  102 => 41,  100 => 22,  97 => 37,  77 => 58,  34 => 16,  480 => 290,  474 => 164,  469 => 162,  461 => 160,  457 => 153,  453 => 101,  444 => 98,  440 => 272,  437 => 138,  435 => 270,  430 => 94,  427 => 143,  423 => 149,  413 => 146,  409 => 132,  407 => 131,  402 => 143,  398 => 243,  393 => 156,  387 => 122,  384 => 237,  381 => 150,  379 => 236,  374 => 116,  368 => 112,  365 => 142,  362 => 110,  360 => 140,  355 => 106,  341 => 121,  337 => 96,  322 => 201,  314 => 99,  312 => 89,  309 => 97,  305 => 182,  298 => 84,  294 => 136,  285 => 168,  283 => 107,  278 => 86,  268 => 104,  264 => 98,  258 => 81,  252 => 59,  247 => 117,  241 => 149,  235 => 74,  229 => 138,  224 => 71,  220 => 63,  214 => 69,  208 => 73,  169 => 50,  143 => 45,  140 => 56,  132 => 86,  128 => 55,  119 => 42,  107 => 44,  71 => 30,  38 => 32,  177 => 78,  165 => 64,  160 => 70,  135 => 58,  126 => 83,  114 => 23,  84 => 62,  70 => 33,  67 => 27,  61 => 25,  94 => 21,  89 => 40,  85 => 32,  75 => 35,  68 => 49,  56 => 40,  196 => 71,  183 => 84,  171 => 61,  166 => 73,  163 => 49,  158 => 67,  156 => 68,  151 => 66,  142 => 51,  138 => 61,  136 => 48,  121 => 43,  117 => 36,  105 => 26,  91 => 38,  62 => 29,  49 => 20,  87 => 59,  21 => 12,  31 => 15,  25 => 12,  26 => 14,  28 => 14,  24 => 13,  19 => 11,  93 => 43,  88 => 31,  78 => 29,  46 => 35,  44 => 19,  27 => 14,  79 => 31,  72 => 25,  69 => 32,  47 => 19,  40 => 18,  37 => 17,  22 => 12,  246 => 70,  157 => 82,  145 => 63,  139 => 60,  131 => 55,  123 => 54,  120 => 53,  115 => 75,  111 => 39,  108 => 47,  101 => 38,  98 => 47,  96 => 43,  83 => 15,  74 => 31,  66 => 25,  55 => 22,  52 => 21,  50 => 17,  43 => 18,  41 => 18,  35 => 16,  32 => 16,  29 => 15,  209 => 82,  203 => 128,  199 => 20,  193 => 129,  189 => 71,  187 => 68,  182 => 66,  176 => 62,  173 => 76,  168 => 66,  164 => 59,  162 => 62,  154 => 50,  149 => 53,  147 => 64,  144 => 92,  141 => 61,  133 => 47,  130 => 56,  125 => 55,  122 => 50,  116 => 36,  112 => 49,  109 => 47,  106 => 46,  103 => 45,  99 => 44,  95 => 64,  92 => 67,  86 => 39,  82 => 57,  80 => 36,  73 => 19,  64 => 26,  60 => 25,  57 => 22,  54 => 25,  51 => 38,  48 => 40,  45 => 19,  42 => 18,  39 => 17,  36 => 16,  33 => 16,  30 => 15,);
    }
}
