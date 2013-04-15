<?php

/* SonataDoctrineORMAdminBundle:CRUD:edit_orm_many_to_one.html.twig */
class __TwigTemplate_016947d191e9713bc34d86af9a93e652 extends Twig_Template
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
        if ((!$this->getAttribute($this->getAttribute($this->getContext($context, "sonata_admin"), "field_description"), "hasassociationadmin"))) {
            // line 13
            echo "    ";
            echo twig_escape_filter($this->env, $this->env->getExtension('sonata_admin')->renderRelationElement($this->getContext($context, "value"), $this->getAttribute($this->getContext($context, "sonata_admin"), "field_description")), "html", null, true);
            echo "
";
        } elseif (($this->getAttribute($this->getContext($context, "sonata_admin"), "edit") == "inline")) {
            // line 15
            echo "    ";
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute($this->getAttribute($this->getContext($context, "sonata_admin"), "field_description"), "associationadmin"), "formfielddescriptions"));
            foreach ($context['_seq'] as $context["_key"] => $context["field_description"]) {
                // line 16
                echo "        ";
                echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute($this->getContext($context, "form"), "get", array(0 => $this->getAttribute($this->getContext($context, "field_description"), "name")), "method"), 'row');
                echo "
    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['field_description'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
        } else {
            // line 19
            echo "    <div id=\"field_container_";
            echo twig_escape_filter($this->env, $this->getContext($context, "id"), "html", null, true);
            echo "\" class=\"field-container\">
        ";
            // line 20
            if (($this->getAttribute($this->getContext($context, "sonata_admin"), "edit") == "list")) {
                // line 21
                echo "            <span id=\"field_widget_";
                echo twig_escape_filter($this->env, $this->getContext($context, "id"), "html", null, true);
                echo "\" >
                ";
                // line 22
                if ($this->getAttribute($this->getAttribute($this->getContext($context, "sonata_admin"), "admin"), "id", array(0 => $this->getAttribute($this->getContext($context, "sonata_admin"), "value")), "method")) {
                    // line 23
                    echo "                    ";
                    echo $this->env->getExtension('actions')->renderAction("sonata.admin.controller.admin:getShortObjectDescriptionAction", array(), array("query" => array("code" => $this->getAttribute($this->getAttribute($this->getAttribute($this->getContext($context, "sonata_admin"), "field_description"), "associationadmin"), "code"), "objectId" => $this->getAttribute($this->getAttribute($this->getAttribute($this->getContext($context, "sonata_admin"), "field_description"), "associationadmin"), "id", array(0 => $this->getAttribute($this->getContext($context, "sonata_admin"), "value")), "method"), "uniqid" => $this->getAttribute($this->getAttribute($this->getAttribute($this->getContext($context, "sonata_admin"), "field_description"), "associationadmin"), "uniqid"))));
                    // line 30
                    echo "                ";
                }
                // line 31
                echo "            </span>
            <span style=\"display: none\" >
                ";
                // line 33
                echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getContext($context, "form"), 'widget');
                echo "
            </span>
        ";
            } else {
                // line 36
                echo "            <span id=\"field_widget_";
                echo twig_escape_filter($this->env, $this->getContext($context, "id"), "html", null, true);
                echo "\" >
                ";
                // line 37
                echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getContext($context, "form"), 'widget');
                echo "
            </span>
        ";
            }
            // line 40
            echo "
        <span id=\"field_actions_";
            // line 41
            echo twig_escape_filter($this->env, $this->getContext($context, "id"), "html", null, true);
            echo "\" class=\"field-actions\">

            ";
            // line 43
            if (((($this->getAttribute($this->getContext($context, "sonata_admin"), "edit") == "list") && $this->getAttribute($this->getAttribute($this->getAttribute($this->getContext($context, "sonata_admin"), "field_description"), "associationadmin"), "hasRoute", array(0 => "list"), "method")) && $this->getAttribute($this->getAttribute($this->getAttribute($this->getContext($context, "sonata_admin"), "field_description"), "associationadmin"), "isGranted", array(0 => "LIST"), "method"))) {
                // line 44
                echo "                <a  href=\"";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute($this->getContext($context, "sonata_admin"), "field_description"), "associationadmin"), "generateUrl", array(0 => "list"), "method"), "html", null, true);
                echo "\"
                    onclick=\"return start_field_dialog_form_list_";
                // line 45
                echo twig_escape_filter($this->env, $this->getContext($context, "id"), "html", null, true);
                echo "(this);\"
                    class=\"btn sonata-ba-action\"
                    title=\"";
                // line 47
                echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("link_list", array(), "SonataAdminBundle"), "html", null, true);
                echo "\"
                    >
                    <i class=\"icon-list\"></i>
                    ";
                // line 50
                echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("link_list", array(), "SonataAdminBundle"), "html", null, true);
                echo "
                </a>
            ";
            }
            // line 53
            echo "
            ";
            // line 54
            if (((($this->getAttribute($this->getContext($context, "sonata_admin"), "edit") != "admin") && $this->getAttribute($this->getAttribute($this->getAttribute($this->getContext($context, "sonata_admin"), "field_description"), "associationadmin"), "hasRoute", array(0 => "create"), "method")) && $this->getAttribute($this->getAttribute($this->getAttribute($this->getContext($context, "sonata_admin"), "field_description"), "associationadmin"), "isGranted", array(0 => "CREATE"), "method"))) {
                // line 55
                echo "                <a  href=\"";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute($this->getContext($context, "sonata_admin"), "field_description"), "associationadmin"), "generateUrl", array(0 => "create"), "method"), "html", null, true);
                echo "\"
                    onclick=\"return start_field_dialog_form_add_";
                // line 56
                echo twig_escape_filter($this->env, $this->getContext($context, "id"), "html", null, true);
                echo "(this);\"
                    class=\"btn sonata-ba-action\"
                    title=\"";
                // line 58
                echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("link_add", array(), "SonataAdminBundle"), "html", null, true);
                echo "\"
                    >
                    <i class=\"icon-plus\"></i>
                    ";
                // line 61
                echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("link_add", array(), "SonataAdminBundle"), "html", null, true);
                echo "
                </a>
            ";
            }
            // line 64
            echo "
            ";
            // line 65
            if (((($this->getAttribute($this->getContext($context, "sonata_admin"), "edit") == "list") && $this->getAttribute($this->getAttribute($this->getAttribute($this->getContext($context, "sonata_admin"), "field_description"), "associationadmin"), "hasRoute", array(0 => "list"), "method")) && $this->getAttribute($this->getAttribute($this->getAttribute($this->getContext($context, "sonata_admin"), "field_description"), "associationadmin"), "isGranted", array(0 => "LIST"), "method"))) {
                // line 66
                echo "                <a  href=\"\"
                    onclick=\"return remove_selected_element_";
                // line 67
                echo twig_escape_filter($this->env, $this->getContext($context, "id"), "html", null, true);
                echo "(this);\"
                    class=\"btn sonata-ba-action\"
                    title=\"";
                // line 69
                echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("link_delete", array(), "SonataAdminBundle"), "html", null, true);
                echo "\"
                    >
                    <i class=\"icon-off\"></i>
                    ";
                // line 72
                echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("link_delete", array(), "SonataAdminBundle"), "html", null, true);
                echo "
                </a>
            ";
            }
            // line 75
            echo "
        </span>

        <div class=\"container sonata-ba-modal sonata-ba-modal-edit-one-to-one\" style=\"display: none\" id=\"field_dialog_";
            // line 78
            echo twig_escape_filter($this->env, $this->getContext($context, "id"), "html", null, true);
            echo "\">

        </div>
    </div>

    ";
            // line 83
            $this->env->loadTemplate("SonataDoctrineORMAdminBundle:CRUD:edit_orm_many_association_script.html.twig")->display($context);
        }
    }

    public function getTemplateName()
    {
        return "SonataDoctrineORMAdminBundle:CRUD:edit_orm_many_to_one.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  183 => 83,  170 => 75,  158 => 69,  145 => 64,  133 => 58,  65 => 31,  30 => 15,  24 => 13,  22 => 12,  19 => 11,  1176 => 331,  1170 => 330,  1164 => 329,  1158 => 328,  1152 => 327,  1146 => 326,  1140 => 325,  1134 => 324,  1128 => 323,  1112 => 317,  1105 => 316,  1103 => 315,  1100 => 314,  1077 => 310,  1052 => 309,  1050 => 308,  1047 => 307,  1035 => 302,  1030 => 301,  1028 => 300,  1025 => 299,  1016 => 293,  1010 => 291,  1007 => 290,  1002 => 289,  1000 => 288,  997 => 287,  990 => 282,  983 => 280,  980 => 276,  976 => 275,  973 => 274,  970 => 273,  968 => 272,  965 => 271,  957 => 267,  955 => 266,  952 => 265,  945 => 260,  942 => 259,  934 => 254,  930 => 253,  926 => 252,  923 => 251,  921 => 250,  918 => 249,  910 => 245,  908 => 241,  906 => 240,  903 => 239,  882 => 233,  879 => 232,  876 => 231,  873 => 230,  870 => 229,  867 => 228,  864 => 227,  861 => 226,  858 => 225,  855 => 224,  853 => 223,  850 => 222,  842 => 216,  839 => 215,  837 => 214,  834 => 213,  826 => 209,  823 => 208,  821 => 207,  818 => 206,  810 => 202,  807 => 201,  805 => 200,  802 => 199,  794 => 195,  791 => 194,  789 => 193,  786 => 192,  778 => 188,  775 => 187,  773 => 186,  770 => 185,  762 => 181,  759 => 180,  757 => 179,  754 => 178,  746 => 174,  744 => 173,  741 => 172,  733 => 168,  730 => 167,  728 => 166,  725 => 165,  717 => 161,  714 => 160,  712 => 159,  710 => 158,  707 => 157,  700 => 152,  692 => 151,  687 => 150,  681 => 148,  678 => 147,  676 => 146,  673 => 145,  665 => 139,  663 => 138,  662 => 137,  661 => 136,  660 => 135,  655 => 134,  649 => 132,  646 => 131,  644 => 130,  641 => 129,  632 => 123,  628 => 122,  620 => 120,  615 => 119,  609 => 117,  606 => 116,  604 => 115,  601 => 114,  583 => 109,  580 => 108,  564 => 104,  559 => 102,  542 => 98,  530 => 96,  523 => 93,  516 => 91,  513 => 90,  493 => 88,  490 => 87,  481 => 82,  478 => 81,  469 => 78,  467 => 77,  459 => 75,  448 => 71,  440 => 70,  438 => 69,  429 => 64,  412 => 60,  407 => 59,  393 => 52,  387 => 50,  384 => 49,  382 => 48,  379 => 47,  369 => 43,  367 => 42,  364 => 41,  350 => 35,  347 => 34,  345 => 33,  342 => 32,  334 => 27,  323 => 24,  316 => 22,  314 => 21,  311 => 20,  290 => 14,  287 => 13,  278 => 8,  267 => 4,  258 => 330,  256 => 329,  248 => 325,  246 => 324,  244 => 323,  241 => 322,  231 => 307,  218 => 287,  215 => 286,  210 => 270,  195 => 249,  192 => 248,  187 => 238,  179 => 221,  176 => 219,  174 => 213,  171 => 212,  166 => 205,  159 => 192,  154 => 185,  141 => 171,  136 => 164,  131 => 156,  129 => 145,  126 => 144,  119 => 114,  116 => 113,  114 => 108,  111 => 107,  109 => 102,  106 => 47,  94 => 43,  74 => 20,  61 => 2,  50 => 20,  45 => 19,  31 => 4,  28 => 3,  26 => 2,  20 => 1,  686 => 206,  680 => 203,  677 => 202,  675 => 201,  669 => 198,  659 => 197,  654 => 195,  642 => 193,  639 => 192,  636 => 191,  627 => 185,  624 => 121,  607 => 182,  590 => 181,  585 => 110,  581 => 178,  578 => 177,  575 => 176,  572 => 175,  566 => 171,  562 => 103,  560 => 168,  555 => 167,  538 => 165,  521 => 92,  517 => 163,  512 => 162,  509 => 161,  506 => 160,  503 => 159,  500 => 158,  498 => 157,  495 => 89,  486 => 151,  482 => 149,  480 => 148,  477 => 147,  475 => 80,  472 => 145,  468 => 125,  462 => 76,  456 => 74,  453 => 120,  450 => 72,  443 => 140,  437 => 138,  435 => 68,  432 => 136,  426 => 133,  423 => 132,  421 => 62,  416 => 61,  405 => 58,  402 => 57,  400 => 119,  391 => 118,  386 => 115,  380 => 112,  377 => 111,  375 => 110,  371 => 109,  366 => 107,  359 => 106,  356 => 37,  353 => 36,  343 => 98,  340 => 97,  337 => 96,  331 => 94,  329 => 26,  324 => 92,  321 => 23,  318 => 90,  312 => 88,  310 => 87,  302 => 86,  298 => 84,  286 => 80,  282 => 79,  277 => 78,  274 => 77,  250 => 326,  247 => 66,  243 => 65,  236 => 314,  223 => 298,  203 => 56,  200 => 259,  197 => 258,  178 => 45,  173 => 42,  152 => 38,  149 => 178,  146 => 177,  139 => 61,  115 => 27,  107 => 24,  101 => 45,  95 => 20,  90 => 18,  87 => 17,  84 => 41,  81 => 40,  79 => 32,  57 => 22,  52 => 21,  47 => 75,  44 => 74,  42 => 8,  39 => 61,  34 => 53,  301 => 137,  295 => 16,  292 => 15,  289 => 81,  281 => 129,  275 => 127,  263 => 71,  257 => 121,  254 => 328,  249 => 118,  237 => 110,  233 => 313,  227 => 105,  221 => 102,  216 => 100,  213 => 271,  202 => 262,  196 => 91,  191 => 50,  186 => 88,  184 => 236,  181 => 86,  175 => 78,  169 => 206,  164 => 72,  160 => 77,  157 => 41,  155 => 75,  150 => 66,  144 => 172,  137 => 67,  132 => 59,  123 => 55,  120 => 56,  104 => 87,  98 => 21,  92 => 19,  86 => 40,  80 => 37,  78 => 40,  75 => 36,  70 => 33,  62 => 30,  59 => 23,  54 => 12,  51 => 24,  38 => 17,  272 => 6,  269 => 5,  264 => 3,  260 => 331,  255 => 70,  252 => 327,  245 => 115,  240 => 66,  238 => 320,  234 => 63,  228 => 306,  226 => 299,  220 => 296,  217 => 58,  211 => 98,  208 => 265,  205 => 264,  199 => 52,  193 => 50,  190 => 239,  188 => 48,  185 => 47,  182 => 222,  177 => 42,  172 => 16,  167 => 79,  163 => 77,  161 => 198,  156 => 191,  153 => 67,  151 => 184,  148 => 65,  140 => 68,  134 => 157,  128 => 56,  125 => 34,  121 => 54,  112 => 50,  110 => 25,  105 => 26,  89 => 41,  83 => 20,  76 => 31,  72 => 191,  67 => 175,  64 => 3,  58 => 12,  53 => 10,  40 => 7,  37 => 54,  35 => 16,  32 => 13,  29 => 11,  23 => 1,  127 => 48,  124 => 129,  118 => 53,  113 => 40,  108 => 38,  102 => 36,  99 => 68,  96 => 44,  91 => 56,  85 => 30,  82 => 29,  77 => 27,  71 => 19,  69 => 33,  66 => 12,  63 => 14,  55 => 11,  49 => 103,  46 => 21,  43 => 20,  12 => 45,);
    }
}
