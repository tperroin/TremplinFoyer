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
        return array (  183 => 83,  158 => 69,  295 => 135,  292 => 134,  281 => 129,  275 => 127,  237 => 110,  221 => 102,  202 => 94,  150 => 66,  160 => 77,  129 => 55,  264 => 72,  260 => 71,  226 => 61,  220 => 59,  199 => 52,  188 => 48,  177 => 42,  161 => 46,  156 => 65,  148 => 65,  124 => 47,  113 => 40,  99 => 23,  12 => 45,  638 => 211,  633 => 209,  625 => 206,  619 => 202,  606 => 198,  602 => 197,  591 => 195,  586 => 193,  579 => 191,  571 => 189,  568 => 188,  565 => 187,  545 => 103,  534 => 98,  529 => 97,  526 => 96,  514 => 85,  511 => 84,  507 => 108,  505 => 96,  499 => 84,  496 => 83,  493 => 82,  489 => 177,  469 => 168,  459 => 165,  442 => 161,  413 => 152,  410 => 151,  394 => 145,  389 => 143,  379 => 137,  370 => 134,  357 => 132,  347 => 130,  341 => 127,  301 => 137,  265 => 109,  262 => 82,  249 => 118,  217 => 58,  214 => 65,  210 => 64,  185 => 47,  182 => 46,  174 => 51,  117 => 47,  140 => 68,  97 => 39,  679 => 218,  676 => 217,  671 => 210,  665 => 207,  656 => 203,  651 => 201,  645 => 199,  643 => 198,  640 => 197,  634 => 195,  632 => 194,  629 => 193,  623 => 191,  621 => 190,  618 => 189,  612 => 187,  610 => 199,  604 => 184,  599 => 157,  595 => 196,  589 => 153,  580 => 150,  570 => 148,  567 => 147,  559 => 145,  553 => 111,  549 => 104,  546 => 109,  532 => 105,  524 => 103,  504 => 97,  501 => 94,  492 => 94,  485 => 88,  474 => 170,  471 => 85,  465 => 49,  461 => 166,  457 => 47,  448 => 44,  431 => 158,  425 => 36,  415 => 153,  411 => 31,  403 => 28,  393 => 222,  384 => 140,  382 => 184,  369 => 179,  360 => 175,  355 => 172,  352 => 171,  338 => 170,  332 => 123,  305 => 164,  299 => 161,  291 => 157,  287 => 155,  285 => 145,  278 => 142,  266 => 138,  252 => 119,  241 => 73,  235 => 130,  227 => 105,  222 => 68,  193 => 50,  179 => 112,  166 => 91,  137 => 67,  86 => 40,  335 => 94,  326 => 90,  306 => 87,  303 => 163,  283 => 84,  279 => 115,  276 => 81,  273 => 112,  271 => 79,  268 => 110,  259 => 81,  255 => 70,  245 => 115,  218 => 57,  211 => 98,  206 => 51,  190 => 49,  187 => 49,  169 => 80,  167 => 79,  164 => 72,  134 => 60,  77 => 28,  65 => 31,  56 => 26,  53 => 23,  686 => 206,  680 => 203,  677 => 202,  675 => 201,  669 => 198,  659 => 204,  654 => 202,  642 => 193,  639 => 192,  636 => 191,  627 => 207,  624 => 184,  607 => 185,  590 => 181,  585 => 179,  581 => 178,  578 => 177,  575 => 149,  572 => 175,  566 => 171,  562 => 146,  560 => 106,  555 => 167,  538 => 99,  521 => 102,  517 => 101,  512 => 99,  509 => 98,  506 => 160,  503 => 159,  500 => 158,  498 => 95,  495 => 156,  486 => 151,  482 => 172,  480 => 148,  477 => 147,  475 => 146,  472 => 169,  462 => 123,  453 => 46,  450 => 163,  437 => 138,  435 => 137,  432 => 136,  423 => 155,  421 => 35,  416 => 129,  405 => 127,  402 => 149,  400 => 148,  391 => 144,  377 => 111,  375 => 182,  371 => 109,  366 => 178,  356 => 105,  353 => 131,  343 => 128,  337 => 96,  331 => 92,  329 => 122,  324 => 119,  318 => 90,  312 => 89,  310 => 118,  302 => 86,  298 => 84,  289 => 133,  286 => 85,  274 => 77,  272 => 75,  269 => 125,  254 => 120,  250 => 67,  247 => 66,  243 => 65,  238 => 65,  236 => 63,  233 => 108,  208 => 97,  203 => 62,  200 => 61,  197 => 60,  175 => 78,  173 => 70,  112 => 50,  110 => 51,  90 => 39,  87 => 17,  69 => 24,  49 => 24,  23 => 11,  82 => 30,  62 => 30,  40 => 19,  20 => 1,  479 => 87,  473 => 161,  468 => 125,  460 => 155,  456 => 164,  452 => 151,  443 => 42,  439 => 41,  436 => 147,  434 => 159,  429 => 144,  426 => 157,  422 => 142,  412 => 134,  408 => 132,  406 => 29,  401 => 130,  397 => 129,  392 => 126,  386 => 115,  383 => 121,  380 => 112,  378 => 183,  373 => 116,  367 => 112,  364 => 177,  361 => 133,  359 => 106,  354 => 106,  340 => 97,  336 => 103,  321 => 91,  313 => 99,  311 => 166,  308 => 97,  304 => 95,  297 => 160,  293 => 158,  284 => 116,  282 => 144,  277 => 114,  267 => 85,  263 => 123,  257 => 121,  251 => 80,  246 => 76,  240 => 66,  234 => 63,  228 => 62,  223 => 58,  219 => 67,  213 => 99,  207 => 63,  198 => 123,  181 => 86,  176 => 111,  170 => 75,  168 => 60,  146 => 46,  142 => 56,  128 => 56,  125 => 51,  107 => 44,  38 => 17,  144 => 70,  141 => 61,  135 => 47,  126 => 51,  109 => 44,  103 => 45,  67 => 32,  61 => 18,  47 => 17,  105 => 26,  93 => 43,  76 => 28,  72 => 25,  68 => 22,  225 => 127,  216 => 100,  212 => 88,  205 => 54,  201 => 124,  196 => 91,  194 => 79,  191 => 89,  189 => 77,  186 => 88,  180 => 72,  172 => 16,  159 => 61,  154 => 59,  147 => 58,  132 => 59,  127 => 54,  121 => 54,  118 => 53,  114 => 46,  104 => 49,  100 => 40,  78 => 40,  75 => 36,  71 => 30,  58 => 12,  34 => 16,  27 => 14,  91 => 38,  84 => 19,  44 => 19,  25 => 12,  28 => 14,  24 => 13,  19 => 11,  94 => 43,  88 => 31,  79 => 16,  59 => 23,  31 => 15,  26 => 13,  21 => 2,  70 => 33,  63 => 28,  46 => 21,  22 => 12,  163 => 77,  155 => 75,  152 => 64,  149 => 47,  145 => 64,  139 => 61,  131 => 55,  123 => 55,  120 => 56,  115 => 50,  106 => 47,  101 => 45,  96 => 44,  83 => 15,  80 => 37,  74 => 15,  66 => 28,  55 => 21,  52 => 20,  50 => 20,  43 => 20,  41 => 18,  37 => 17,  35 => 16,  32 => 16,  29 => 14,  184 => 87,  178 => 52,  171 => 94,  165 => 58,  162 => 57,  157 => 76,  153 => 67,  151 => 42,  143 => 45,  138 => 57,  136 => 50,  133 => 58,  130 => 67,  122 => 50,  119 => 49,  116 => 35,  111 => 45,  108 => 44,  102 => 41,  98 => 47,  95 => 19,  92 => 45,  89 => 41,  85 => 37,  81 => 178,  73 => 27,  64 => 13,  60 => 22,  57 => 22,  54 => 25,  51 => 21,  48 => 15,  45 => 19,  42 => 18,  39 => 16,  36 => 17,  33 => 16,  30 => 15,);
    }
}
