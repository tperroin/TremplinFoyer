<?php

/* SonataAdminBundle:Core:create_button.html.twig */
class __TwigTemplate_efae8341157848990dcf312752a156cb extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 11
        echo "
";
        // line 12
        if (($this->getAttribute($this->getContext($context, "admin"), "hasRoute", array(0 => "create"), "method") && $this->getAttribute($this->getContext($context, "admin"), "isGranted", array(0 => "CREATE"), "method"))) {
            // line 13
            echo "    ";
            if (twig_test_empty($this->getAttribute($this->getContext($context, "admin"), "subClasses"))) {
                // line 14
                echo "        <a class=\"btn sonata-action-element\" href=\"";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "admin"), "generateUrl", array(0 => "create"), "method"), "html", null, true);
                echo "\">
            <i class=\"icon-plus\"></i>
            ";
                // line 16
                echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("link_action_create", array(), "SonataAdminBundle"), "html", null, true);
                echo "</a>
    ";
            } else {
                // line 18
                echo "        <span class=\"btn-group sonata-action-element\">
            <a class=\"btn dropdown-toggle\" data-toggle=\"dropdown\" href=\"#\">
                <i class=\"icon-plus\"></i>
                ";
                // line 21
                echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("link_action_create", array(), "SonataAdminBundle"), "html", null, true);
                echo "
                <span class=\"caret\"></span>
            </a>
            <ul class=\"dropdown-menu\">
                ";
                // line 25
                $context['_parent'] = (array) $context;
                $context['_seq'] = twig_ensure_traversable(twig_get_array_keys_filter($this->getAttribute($this->getContext($context, "admin"), "subclasses")));
                foreach ($context['_seq'] as $context["_key"] => $context["subclass"]) {
                    // line 26
                    echo "                    <li>
                        <a href=\"";
                    // line 27
                    echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "admin"), "generateUrl", array(0 => "create", 1 => array("subclass" => $this->getContext($context, "subclass"))), "method"), "html", null, true);
                    echo "\">";
                    echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans($this->getContext($context, "subclass"), array(), $this->getAttribute($this->getContext($context, "admin"), "translationdomain")), "html", null, true);
                    echo "</a>
                    </li>
                ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['subclass'], $context['_parent'], $context['loop']);
                $context = array_merge($_parent, array_intersect_key($context, $_parent));
                // line 30
                echo "            </ul>
        </span>
    ";
            }
        }
    }

    public function getTemplateName()
    {
        return "SonataAdminBundle:Core:create_button.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  27 => 14,  73 => 32,  48 => 21,  33 => 16,  643 => 389,  634 => 383,  630 => 381,  618 => 374,  613 => 372,  608 => 370,  597 => 361,  593 => 360,  587 => 357,  577 => 353,  567 => 346,  549 => 334,  535 => 326,  531 => 324,  529 => 321,  527 => 320,  511 => 310,  504 => 306,  496 => 301,  492 => 299,  485 => 292,  463 => 284,  458 => 282,  446 => 275,  428 => 265,  420 => 260,  410 => 253,  406 => 252,  398 => 243,  394 => 242,  390 => 240,  370 => 229,  360 => 222,  354 => 219,  305 => 182,  297 => 177,  285 => 168,  276 => 165,  271 => 163,  262 => 157,  222 => 138,  207 => 129,  100 => 66,  68 => 30,  56 => 40,  183 => 83,  170 => 75,  158 => 69,  145 => 64,  133 => 58,  65 => 31,  30 => 15,  24 => 13,  22 => 12,  19 => 11,  1176 => 331,  1170 => 330,  1164 => 329,  1158 => 328,  1152 => 327,  1146 => 326,  1140 => 325,  1134 => 324,  1128 => 323,  1112 => 317,  1105 => 316,  1103 => 315,  1100 => 314,  1077 => 310,  1052 => 309,  1050 => 308,  1047 => 307,  1035 => 302,  1030 => 301,  1028 => 300,  1025 => 299,  1016 => 293,  1010 => 291,  1007 => 290,  1002 => 289,  1000 => 288,  997 => 287,  990 => 282,  983 => 280,  980 => 276,  976 => 275,  973 => 274,  970 => 273,  968 => 272,  965 => 271,  957 => 267,  955 => 266,  952 => 265,  945 => 260,  942 => 259,  934 => 254,  930 => 253,  926 => 252,  923 => 251,  921 => 250,  918 => 249,  910 => 245,  908 => 241,  906 => 240,  903 => 239,  882 => 233,  879 => 232,  876 => 231,  873 => 230,  870 => 229,  867 => 228,  864 => 227,  861 => 226,  858 => 225,  855 => 224,  853 => 223,  850 => 222,  842 => 216,  839 => 215,  837 => 214,  834 => 213,  826 => 209,  823 => 208,  821 => 207,  818 => 206,  810 => 202,  807 => 201,  805 => 200,  802 => 199,  794 => 195,  791 => 194,  789 => 193,  786 => 192,  778 => 188,  775 => 187,  773 => 186,  770 => 185,  762 => 181,  759 => 180,  757 => 179,  754 => 178,  746 => 174,  744 => 173,  741 => 172,  733 => 168,  730 => 167,  728 => 166,  725 => 165,  717 => 161,  714 => 160,  712 => 159,  710 => 158,  707 => 157,  700 => 152,  692 => 151,  687 => 150,  681 => 148,  678 => 147,  676 => 146,  673 => 145,  665 => 139,  663 => 138,  662 => 137,  661 => 136,  660 => 135,  655 => 134,  649 => 132,  646 => 131,  644 => 130,  641 => 129,  632 => 123,  628 => 377,  620 => 120,  615 => 119,  609 => 117,  606 => 116,  604 => 368,  601 => 114,  583 => 356,  580 => 108,  564 => 104,  559 => 341,  542 => 330,  530 => 96,  523 => 93,  516 => 91,  513 => 90,  493 => 88,  490 => 87,  481 => 82,  478 => 81,  469 => 78,  467 => 77,  459 => 75,  448 => 71,  440 => 272,  438 => 69,  429 => 64,  412 => 60,  407 => 59,  393 => 52,  387 => 50,  384 => 237,  382 => 48,  379 => 236,  369 => 43,  367 => 42,  364 => 41,  350 => 35,  347 => 34,  345 => 33,  342 => 210,  334 => 27,  323 => 24,  316 => 22,  314 => 21,  311 => 20,  290 => 14,  287 => 13,  278 => 8,  267 => 4,  258 => 330,  256 => 329,  248 => 153,  246 => 324,  244 => 323,  241 => 149,  231 => 307,  218 => 287,  215 => 286,  210 => 270,  195 => 249,  192 => 248,  187 => 118,  179 => 221,  176 => 219,  174 => 213,  171 => 212,  166 => 205,  159 => 192,  154 => 185,  141 => 171,  136 => 164,  131 => 156,  129 => 145,  126 => 83,  119 => 114,  116 => 113,  114 => 108,  111 => 107,  109 => 102,  106 => 47,  94 => 43,  74 => 20,  61 => 2,  50 => 25,  45 => 20,  31 => 22,  28 => 3,  26 => 20,  20 => 1,  686 => 206,  680 => 203,  677 => 202,  675 => 201,  669 => 198,  659 => 197,  654 => 195,  642 => 193,  639 => 192,  636 => 191,  627 => 185,  624 => 121,  607 => 182,  590 => 181,  585 => 110,  581 => 178,  578 => 177,  575 => 176,  572 => 175,  566 => 171,  562 => 103,  560 => 168,  555 => 167,  538 => 165,  521 => 317,  517 => 163,  512 => 162,  509 => 161,  506 => 160,  503 => 159,  500 => 158,  498 => 157,  495 => 89,  486 => 151,  482 => 149,  480 => 290,  477 => 147,  475 => 80,  472 => 287,  468 => 286,  462 => 76,  456 => 74,  453 => 120,  450 => 72,  443 => 140,  437 => 138,  435 => 270,  432 => 136,  426 => 133,  423 => 132,  421 => 62,  416 => 61,  405 => 58,  402 => 57,  400 => 248,  391 => 118,  386 => 115,  380 => 112,  377 => 232,  375 => 231,  371 => 109,  366 => 107,  359 => 106,  356 => 37,  353 => 36,  343 => 98,  340 => 97,  337 => 96,  331 => 94,  329 => 200,  324 => 92,  321 => 23,  318 => 90,  312 => 88,  310 => 184,  302 => 86,  298 => 84,  286 => 80,  282 => 79,  277 => 78,  274 => 77,  250 => 326,  247 => 66,  243 => 65,  236 => 314,  223 => 298,  203 => 128,  200 => 259,  197 => 258,  178 => 45,  173 => 110,  152 => 38,  149 => 178,  146 => 177,  139 => 61,  115 => 75,  107 => 24,  101 => 45,  95 => 64,  90 => 18,  87 => 59,  84 => 41,  81 => 40,  79 => 32,  57 => 27,  52 => 22,  47 => 75,  44 => 74,  42 => 8,  39 => 18,  34 => 53,  301 => 137,  295 => 16,  292 => 15,  289 => 81,  281 => 167,  275 => 127,  263 => 71,  257 => 121,  254 => 154,  249 => 118,  237 => 110,  233 => 146,  227 => 105,  221 => 102,  216 => 100,  213 => 271,  202 => 262,  196 => 91,  191 => 119,  186 => 88,  184 => 236,  181 => 115,  175 => 78,  169 => 206,  164 => 104,  160 => 77,  157 => 41,  155 => 75,  150 => 93,  144 => 92,  137 => 88,  132 => 86,  123 => 55,  120 => 56,  104 => 87,  98 => 21,  92 => 19,  86 => 40,  80 => 36,  78 => 56,  75 => 36,  70 => 33,  62 => 30,  59 => 23,  54 => 26,  51 => 38,  38 => 18,  272 => 6,  269 => 5,  264 => 3,  260 => 331,  255 => 70,  252 => 327,  245 => 115,  240 => 66,  238 => 320,  234 => 63,  228 => 306,  226 => 299,  220 => 296,  217 => 136,  211 => 98,  208 => 265,  205 => 264,  199 => 52,  193 => 50,  190 => 239,  188 => 48,  185 => 47,  182 => 222,  177 => 42,  172 => 16,  167 => 79,  163 => 77,  161 => 198,  156 => 191,  153 => 67,  151 => 184,  148 => 65,  140 => 68,  134 => 157,  128 => 56,  125 => 34,  121 => 54,  112 => 50,  110 => 25,  105 => 26,  89 => 41,  83 => 20,  76 => 31,  72 => 191,  67 => 175,  64 => 3,  58 => 12,  53 => 10,  40 => 7,  37 => 54,  35 => 16,  32 => 13,  29 => 14,  23 => 18,  127 => 48,  124 => 129,  118 => 53,  113 => 40,  108 => 71,  102 => 36,  99 => 68,  96 => 44,  91 => 56,  85 => 30,  82 => 57,  77 => 27,  71 => 19,  69 => 30,  66 => 12,  63 => 27,  55 => 11,  49 => 103,  46 => 21,  43 => 21,  12 => 45,);
    }
}
