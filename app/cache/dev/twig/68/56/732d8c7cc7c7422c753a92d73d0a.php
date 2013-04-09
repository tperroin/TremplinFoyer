<?php

/* SonataDoctrineORMAdminBundle:CRUD:show_orm_one_to_many.html.twig */
class __TwigTemplate_6856732d8c7cc7c7422c753a92d73d0a extends Twig_Template
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
        echo "    <ul class=\"sonata-ba-show-one-to-many\">
    ";
        // line 16
        if ((($this->getAttribute($this->getContext($context, "field_description"), "hasassociationadmin") && $this->getAttribute($this->getAttribute($this->getContext($context, "field_description"), "associationadmin"), "isGranted", array(0 => "EDIT"), "method")) && $this->getAttribute($this->getAttribute($this->getContext($context, "field_description"), "associationadmin"), "hasRoute", array(0 => "edit"), "method"))) {
            // line 17
            echo "        ";
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getContext($context, "value"));
            foreach ($context['_seq'] as $context["_key"] => $context["element"]) {
                // line 18
                echo "            <li><a href=\"";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getContext($context, "field_description"), "associationadmin"), "generateObjectUrl", array(0 => "edit", 1 => $this->getContext($context, "element")), "method"), "html", null, true);
                echo "\">";
                echo twig_escape_filter($this->env, $this->env->getExtension('sonata_admin')->renderRelationElement($this->getContext($context, "element"), $this->getContext($context, "field_description")), "html", null, true);
                echo "</a></li>
        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['element'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 20
            echo "    ";
        } else {
            // line 21
            echo "        ";
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getContext($context, "value"));
            foreach ($context['_seq'] as $context["_key"] => $context["element"]) {
                // line 22
                echo "            <li>";
                echo twig_escape_filter($this->env, $this->env->getExtension('sonata_admin')->renderRelationElement($this->getContext($context, "element"), $this->getContext($context, "field_description")), "html", null, true);
                echo "</li>
        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['element'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 24
            echo "    ";
        }
        // line 25
        echo "    </ul>
";
    }

    public function getTemplateName()
    {
        return "SonataDoctrineORMAdminBundle:CRUD:show_orm_one_to_many.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  297 => 137,  291 => 135,  256 => 122,  251 => 120,  227 => 105,  221 => 102,  215 => 99,  210 => 97,  207 => 96,  194 => 89,  188 => 86,  161 => 75,  129 => 55,  333 => 85,  330 => 84,  325 => 82,  299 => 75,  290 => 72,  288 => 71,  270 => 65,  267 => 64,  261 => 62,  244 => 56,  239 => 112,  231 => 27,  213 => 23,  170 => 89,  159 => 54,  150 => 66,  124 => 47,  113 => 40,  12 => 45,  631 => 203,  625 => 201,  623 => 200,  617 => 196,  608 => 193,  604 => 192,  595 => 190,  591 => 189,  579 => 185,  571 => 183,  568 => 182,  565 => 181,  549 => 98,  545 => 97,  534 => 92,  529 => 91,  526 => 90,  514 => 79,  511 => 78,  505 => 90,  501 => 88,  496 => 77,  493 => 76,  489 => 171,  459 => 159,  442 => 155,  434 => 153,  431 => 152,  415 => 147,  410 => 145,  394 => 139,  332 => 117,  301 => 76,  284 => 110,  265 => 125,  262 => 76,  257 => 74,  249 => 71,  185 => 84,  174 => 51,  613 => 205,  610 => 204,  605 => 199,  598 => 191,  592 => 192,  588 => 190,  586 => 187,  583 => 188,  577 => 186,  564 => 181,  561 => 180,  553 => 177,  550 => 176,  544 => 174,  542 => 173,  539 => 172,  536 => 171,  531 => 159,  527 => 128,  507 => 102,  502 => 122,  499 => 78,  494 => 120,  491 => 119,  485 => 110,  481 => 109,  478 => 108,  470 => 105,  464 => 104,  449 => 100,  441 => 97,  436 => 96,  433 => 95,  424 => 93,  417 => 87,  411 => 86,  406 => 85,  403 => 84,  397 => 48,  389 => 137,  370 => 128,  367 => 39,  361 => 127,  357 => 126,  351 => 32,  347 => 124,  342 => 29,  339 => 28,  336 => 27,  327 => 204,  320 => 171,  316 => 80,  313 => 79,  307 => 166,  304 => 165,  296 => 74,  253 => 147,  248 => 144,  242 => 142,  234 => 140,  232 => 139,  226 => 65,  205 => 95,  198 => 132,  179 => 111,  155 => 68,  127 => 54,  335 => 94,  326 => 90,  306 => 77,  303 => 139,  279 => 68,  276 => 151,  273 => 66,  271 => 127,  259 => 123,  255 => 60,  245 => 66,  240 => 64,  218 => 57,  211 => 53,  206 => 21,  190 => 50,  181 => 47,  172 => 76,  167 => 78,  134 => 42,  65 => 30,  63 => 28,  686 => 206,  680 => 203,  677 => 202,  675 => 201,  669 => 198,  659 => 197,  654 => 195,  642 => 193,  639 => 192,  636 => 205,  627 => 185,  624 => 184,  607 => 182,  590 => 181,  585 => 179,  581 => 178,  578 => 177,  575 => 185,  572 => 184,  566 => 182,  562 => 169,  560 => 100,  555 => 178,  538 => 93,  521 => 127,  517 => 163,  512 => 124,  509 => 161,  506 => 160,  503 => 159,  500 => 158,  498 => 157,  495 => 156,  486 => 151,  482 => 166,  477 => 147,  475 => 146,  472 => 163,  468 => 125,  462 => 123,  456 => 158,  450 => 157,  443 => 140,  432 => 136,  426 => 151,  421 => 131,  416 => 129,  405 => 127,  400 => 142,  391 => 138,  386 => 115,  380 => 112,  377 => 111,  375 => 41,  371 => 109,  366 => 107,  359 => 106,  356 => 105,  353 => 125,  343 => 122,  340 => 97,  331 => 92,  329 => 116,  324 => 113,  321 => 81,  318 => 90,  310 => 112,  302 => 164,  289 => 156,  286 => 85,  282 => 69,  277 => 129,  274 => 77,  272 => 76,  269 => 75,  263 => 71,  254 => 121,  250 => 58,  243 => 65,  238 => 64,  236 => 50,  233 => 109,  228 => 59,  223 => 64,  200 => 92,  197 => 60,  191 => 56,  184 => 114,  178 => 83,  175 => 16,  146 => 46,  118 => 52,  110 => 51,  104 => 49,  90 => 39,  81 => 172,  59 => 23,  23 => 11,  76 => 28,  58 => 12,  53 => 21,  20 => 1,  152 => 67,  148 => 63,  137 => 43,  102 => 41,  100 => 45,  97 => 44,  77 => 15,  34 => 16,  480 => 148,  474 => 164,  469 => 162,  461 => 160,  457 => 153,  453 => 101,  444 => 98,  440 => 148,  437 => 138,  435 => 137,  430 => 94,  427 => 143,  423 => 149,  413 => 146,  409 => 132,  407 => 131,  402 => 143,  398 => 129,  393 => 47,  387 => 122,  384 => 134,  381 => 120,  379 => 131,  374 => 116,  368 => 112,  365 => 111,  362 => 110,  360 => 109,  355 => 106,  341 => 121,  337 => 96,  322 => 201,  314 => 99,  312 => 89,  309 => 97,  305 => 95,  298 => 84,  294 => 136,  285 => 154,  283 => 131,  278 => 86,  268 => 104,  264 => 84,  258 => 81,  252 => 59,  247 => 117,  241 => 67,  235 => 74,  229 => 138,  224 => 71,  220 => 63,  214 => 69,  208 => 136,  169 => 50,  143 => 45,  140 => 56,  132 => 59,  128 => 55,  119 => 49,  107 => 44,  71 => 30,  38 => 17,  177 => 79,  165 => 64,  160 => 70,  135 => 47,  126 => 51,  114 => 46,  84 => 36,  70 => 33,  67 => 32,  61 => 25,  94 => 42,  89 => 40,  85 => 37,  75 => 35,  68 => 30,  56 => 26,  196 => 90,  183 => 84,  171 => 93,  166 => 73,  163 => 49,  158 => 67,  156 => 73,  151 => 72,  142 => 59,  138 => 61,  136 => 60,  121 => 53,  117 => 36,  105 => 26,  91 => 38,  62 => 29,  49 => 21,  87 => 20,  21 => 11,  31 => 15,  25 => 12,  26 => 14,  28 => 14,  24 => 13,  19 => 11,  93 => 43,  88 => 31,  78 => 40,  46 => 21,  44 => 16,  27 => 14,  79 => 34,  72 => 25,  69 => 24,  47 => 17,  40 => 24,  37 => 17,  22 => 12,  246 => 70,  157 => 82,  145 => 63,  139 => 60,  131 => 55,  123 => 54,  120 => 56,  115 => 50,  111 => 48,  108 => 44,  101 => 44,  98 => 47,  96 => 43,  83 => 15,  74 => 31,  66 => 28,  55 => 21,  52 => 20,  50 => 20,  43 => 19,  41 => 18,  35 => 16,  32 => 16,  29 => 15,  209 => 82,  203 => 62,  199 => 20,  193 => 129,  189 => 71,  187 => 49,  182 => 19,  176 => 82,  173 => 81,  168 => 66,  164 => 56,  162 => 62,  154 => 50,  149 => 71,  147 => 58,  144 => 68,  141 => 67,  133 => 57,  130 => 66,  125 => 51,  122 => 50,  116 => 36,  112 => 49,  109 => 47,  106 => 46,  103 => 45,  99 => 23,  95 => 19,  92 => 45,  86 => 39,  82 => 30,  80 => 36,  73 => 27,  64 => 31,  60 => 22,  57 => 22,  54 => 25,  51 => 21,  48 => 15,  45 => 19,  42 => 18,  39 => 16,  36 => 17,  33 => 16,  30 => 15,);
    }
}
