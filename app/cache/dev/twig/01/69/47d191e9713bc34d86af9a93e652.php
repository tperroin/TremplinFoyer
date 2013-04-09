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
                echo "\" class=\"field-short-description\">
                ";
                // line 22
                if ($this->getAttribute($this->getAttribute($this->getContext($context, "sonata_admin"), "admin"), "id", array(0 => $this->getAttribute($this->getContext($context, "sonata_admin"), "value")), "method")) {
                    // line 23
                    echo "                    ";
                    echo $this->env->getExtension('actions')->renderUri($this->env->getExtension('routing')->getUrl("sonata_admin_short_object_information", array("code" => $this->getAttribute($this->getAttribute($this->getAttribute($this->getContext($context, "sonata_admin"), "field_description"), "associationadmin"), "code"), "objectId" => $this->getAttribute($this->getAttribute($this->getAttribute($this->getContext($context, "sonata_admin"), "field_description"), "associationadmin"), "id", array(0 => $this->getAttribute($this->getContext($context, "sonata_admin"), "value")), "method"), "uniqid" => $this->getAttribute($this->getAttribute($this->getAttribute($this->getContext($context, "sonata_admin"), "field_description"), "associationadmin"), "uniqid"))), array());
                    // line 29
                    echo "                ";
                }
                // line 30
                echo "            </span>
            <span style=\"display: none\" >
                ";
                // line 32
                echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getContext($context, "form"), 'widget');
                echo "
            </span>
        ";
            } else {
                // line 35
                echo "            <span id=\"field_widget_";
                echo twig_escape_filter($this->env, $this->getContext($context, "id"), "html", null, true);
                echo "\" >
                ";
                // line 36
                echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getContext($context, "form"), 'widget');
                echo "
            </span>
        ";
            }
            // line 39
            echo "
        <span id=\"field_actions_";
            // line 40
            echo twig_escape_filter($this->env, $this->getContext($context, "id"), "html", null, true);
            echo "\" class=\"field-actions\">
            <span class=\"btn-group\">
                ";
            // line 42
            if (((($this->getAttribute($this->getContext($context, "sonata_admin"), "edit") == "list") && $this->getAttribute($this->getAttribute($this->getAttribute($this->getContext($context, "sonata_admin"), "field_description"), "associationadmin"), "hasRoute", array(0 => "list"), "method")) && $this->getAttribute($this->getAttribute($this->getAttribute($this->getContext($context, "sonata_admin"), "field_description"), "associationadmin"), "isGranted", array(0 => "LIST"), "method"))) {
                // line 43
                echo "                    <a  href=\"";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute($this->getContext($context, "sonata_admin"), "field_description"), "associationadmin"), "generateUrl", array(0 => "list"), "method"), "html", null, true);
                echo "\"
                        onclick=\"return start_field_dialog_form_list_";
                // line 44
                echo twig_escape_filter($this->env, $this->getContext($context, "id"), "html", null, true);
                echo "(this);\"
                        class=\"btn sonata-ba-action\"
                        title=\"";
                // line 46
                echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("link_list", array(), "SonataAdminBundle"), "html", null, true);
                echo "\"
                        >
                        <i class=\"icon-list\"></i>
                        ";
                // line 49
                echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("link_list", array(), "SonataAdminBundle"), "html", null, true);
                echo "
                    </a>
                ";
            }
            // line 52
            echo "
                ";
            // line 53
            if (((($this->getAttribute($this->getContext($context, "sonata_admin"), "edit") != "admin") && $this->getAttribute($this->getAttribute($this->getAttribute($this->getContext($context, "sonata_admin"), "field_description"), "associationadmin"), "hasRoute", array(0 => "create"), "method")) && $this->getAttribute($this->getAttribute($this->getAttribute($this->getContext($context, "sonata_admin"), "field_description"), "associationadmin"), "isGranted", array(0 => "CREATE"), "method"))) {
                // line 54
                echo "                    <a  href=\"";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute($this->getContext($context, "sonata_admin"), "field_description"), "associationadmin"), "generateUrl", array(0 => "create"), "method"), "html", null, true);
                echo "\"
                        onclick=\"return start_field_dialog_form_add_";
                // line 55
                echo twig_escape_filter($this->env, $this->getContext($context, "id"), "html", null, true);
                echo "(this);\"
                        class=\"btn sonata-ba-action\"
                        title=\"";
                // line 57
                echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("link_add", array(), "SonataAdminBundle"), "html", null, true);
                echo "\"
                        >
                        <i class=\"icon-plus\"></i>
                        ";
                // line 60
                echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("link_add", array(), "SonataAdminBundle"), "html", null, true);
                echo "
                    </a>
                ";
            }
            // line 63
            echo "            </span>

            <span class=\"btn-group\">
                ";
            // line 66
            if (((($this->getAttribute($this->getContext($context, "sonata_admin"), "edit") == "list") && $this->getAttribute($this->getAttribute($this->getAttribute($this->getContext($context, "sonata_admin"), "field_description"), "associationadmin"), "hasRoute", array(0 => "list"), "method")) && $this->getAttribute($this->getAttribute($this->getAttribute($this->getContext($context, "sonata_admin"), "field_description"), "associationadmin"), "isGranted", array(0 => "LIST"), "method"))) {
                // line 67
                echo "                    <a  href=\"\"
                        onclick=\"return remove_selected_element_";
                // line 68
                echo twig_escape_filter($this->env, $this->getContext($context, "id"), "html", null, true);
                echo "(this);\"
                        class=\"btn sonata-ba-action\"
                        title=\"";
                // line 70
                echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("link_delete", array(), "SonataAdminBundle"), "html", null, true);
                echo "\"
                        >
                        <i class=\"icon-off\"></i>
                        ";
                // line 73
                echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("link_delete", array(), "SonataAdminBundle"), "html", null, true);
                echo "
                    </a>
                ";
            }
            // line 76
            echo "            </span>
        </span>

        <div class=\"container sonata-ba-modal sonata-ba-modal-edit-one-to-one\" style=\"display: none\" id=\"field_dialog_";
            // line 79
            echo twig_escape_filter($this->env, $this->getContext($context, "id"), "html", null, true);
            echo "\">

        </div>
    </div>

    ";
            // line 84
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
        return array (  297 => 137,  291 => 135,  256 => 122,  251 => 120,  227 => 105,  221 => 102,  215 => 99,  210 => 97,  207 => 96,  194 => 89,  188 => 86,  161 => 75,  129 => 55,  333 => 85,  330 => 84,  325 => 82,  299 => 75,  290 => 72,  288 => 71,  270 => 65,  267 => 64,  261 => 62,  244 => 56,  239 => 112,  231 => 27,  213 => 23,  170 => 89,  159 => 54,  150 => 66,  124 => 47,  113 => 40,  12 => 45,  631 => 203,  625 => 201,  623 => 200,  617 => 196,  608 => 193,  604 => 192,  595 => 190,  591 => 189,  579 => 185,  571 => 183,  568 => 182,  565 => 181,  549 => 98,  545 => 97,  534 => 92,  529 => 91,  526 => 90,  514 => 79,  511 => 78,  505 => 90,  501 => 88,  496 => 77,  493 => 76,  489 => 171,  459 => 159,  442 => 155,  434 => 153,  431 => 152,  415 => 147,  410 => 145,  394 => 139,  332 => 117,  301 => 76,  284 => 110,  265 => 125,  262 => 76,  257 => 74,  249 => 71,  185 => 84,  174 => 51,  613 => 205,  610 => 204,  605 => 199,  598 => 191,  592 => 192,  588 => 190,  586 => 187,  583 => 188,  577 => 186,  564 => 181,  561 => 180,  553 => 177,  550 => 176,  544 => 174,  542 => 173,  539 => 172,  536 => 171,  531 => 159,  527 => 128,  507 => 102,  502 => 122,  499 => 78,  494 => 120,  491 => 119,  485 => 110,  481 => 109,  478 => 108,  470 => 105,  464 => 104,  449 => 100,  441 => 97,  436 => 96,  433 => 95,  424 => 93,  417 => 87,  411 => 86,  406 => 85,  403 => 84,  397 => 48,  389 => 137,  370 => 128,  367 => 39,  361 => 127,  357 => 126,  351 => 32,  347 => 124,  342 => 29,  339 => 28,  336 => 27,  327 => 204,  320 => 171,  316 => 80,  313 => 79,  307 => 166,  304 => 165,  296 => 74,  253 => 147,  248 => 144,  242 => 142,  234 => 140,  232 => 139,  226 => 65,  205 => 95,  198 => 132,  179 => 111,  155 => 68,  127 => 54,  335 => 94,  326 => 90,  306 => 77,  303 => 139,  279 => 68,  276 => 151,  273 => 66,  271 => 127,  259 => 123,  255 => 60,  245 => 66,  240 => 64,  218 => 57,  211 => 53,  206 => 21,  190 => 50,  181 => 47,  172 => 76,  167 => 78,  134 => 42,  65 => 30,  63 => 28,  686 => 206,  680 => 203,  677 => 202,  675 => 201,  669 => 198,  659 => 197,  654 => 195,  642 => 193,  639 => 192,  636 => 205,  627 => 185,  624 => 184,  607 => 182,  590 => 181,  585 => 179,  581 => 178,  578 => 177,  575 => 185,  572 => 184,  566 => 182,  562 => 169,  560 => 100,  555 => 178,  538 => 93,  521 => 127,  517 => 163,  512 => 124,  509 => 161,  506 => 160,  503 => 159,  500 => 158,  498 => 157,  495 => 156,  486 => 151,  482 => 166,  477 => 147,  475 => 146,  472 => 163,  468 => 125,  462 => 123,  456 => 158,  450 => 157,  443 => 140,  432 => 136,  426 => 151,  421 => 131,  416 => 129,  405 => 127,  400 => 142,  391 => 138,  386 => 115,  380 => 112,  377 => 111,  375 => 41,  371 => 109,  366 => 107,  359 => 106,  356 => 105,  353 => 125,  343 => 122,  340 => 97,  331 => 92,  329 => 116,  324 => 113,  321 => 81,  318 => 90,  310 => 112,  302 => 164,  289 => 156,  286 => 85,  282 => 69,  277 => 129,  274 => 77,  272 => 76,  269 => 75,  263 => 71,  254 => 121,  250 => 58,  243 => 65,  238 => 64,  236 => 50,  233 => 109,  228 => 59,  223 => 64,  200 => 92,  197 => 60,  191 => 56,  184 => 114,  178 => 83,  175 => 16,  146 => 46,  118 => 52,  110 => 51,  104 => 49,  90 => 39,  81 => 172,  59 => 23,  23 => 11,  76 => 28,  58 => 12,  53 => 21,  20 => 1,  152 => 67,  148 => 63,  137 => 43,  102 => 41,  100 => 45,  97 => 44,  77 => 15,  34 => 16,  480 => 148,  474 => 164,  469 => 162,  461 => 160,  457 => 153,  453 => 101,  444 => 98,  440 => 148,  437 => 138,  435 => 137,  430 => 94,  427 => 143,  423 => 149,  413 => 146,  409 => 132,  407 => 131,  402 => 143,  398 => 129,  393 => 47,  387 => 122,  384 => 134,  381 => 120,  379 => 131,  374 => 116,  368 => 112,  365 => 111,  362 => 110,  360 => 109,  355 => 106,  341 => 121,  337 => 96,  322 => 201,  314 => 99,  312 => 89,  309 => 97,  305 => 95,  298 => 84,  294 => 136,  285 => 154,  283 => 131,  278 => 86,  268 => 104,  264 => 84,  258 => 81,  252 => 59,  247 => 117,  241 => 67,  235 => 74,  229 => 138,  224 => 71,  220 => 63,  214 => 69,  208 => 136,  169 => 50,  143 => 45,  140 => 56,  132 => 59,  128 => 55,  119 => 49,  107 => 44,  71 => 30,  38 => 17,  177 => 79,  165 => 64,  160 => 70,  135 => 47,  126 => 51,  114 => 46,  84 => 36,  70 => 33,  67 => 32,  61 => 25,  94 => 42,  89 => 40,  85 => 37,  75 => 35,  68 => 30,  56 => 26,  196 => 90,  183 => 84,  171 => 93,  166 => 73,  163 => 49,  158 => 67,  156 => 73,  151 => 72,  142 => 59,  138 => 61,  136 => 60,  121 => 53,  117 => 36,  105 => 26,  91 => 38,  62 => 29,  49 => 21,  87 => 20,  21 => 11,  31 => 15,  25 => 12,  26 => 14,  28 => 14,  24 => 13,  19 => 11,  93 => 43,  88 => 31,  78 => 40,  46 => 21,  44 => 16,  27 => 14,  79 => 34,  72 => 37,  69 => 32,  47 => 17,  40 => 24,  37 => 17,  22 => 12,  246 => 70,  157 => 82,  145 => 63,  139 => 60,  131 => 55,  123 => 54,  120 => 56,  115 => 50,  111 => 48,  108 => 44,  101 => 44,  98 => 47,  96 => 43,  83 => 15,  74 => 31,  66 => 28,  55 => 25,  52 => 21,  50 => 20,  43 => 19,  41 => 19,  35 => 16,  32 => 16,  29 => 15,  209 => 82,  203 => 62,  199 => 20,  193 => 129,  189 => 71,  187 => 49,  182 => 19,  176 => 82,  173 => 81,  168 => 66,  164 => 56,  162 => 62,  154 => 50,  149 => 71,  147 => 58,  144 => 68,  141 => 67,  133 => 57,  130 => 66,  125 => 51,  122 => 50,  116 => 36,  112 => 49,  109 => 47,  106 => 46,  103 => 45,  99 => 23,  95 => 19,  92 => 45,  86 => 39,  82 => 30,  80 => 36,  73 => 27,  64 => 31,  60 => 15,  57 => 22,  54 => 25,  51 => 21,  48 => 15,  45 => 19,  42 => 17,  39 => 16,  36 => 16,  33 => 16,  30 => 15,);
    }
}
