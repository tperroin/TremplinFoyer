<?php

/* SonataAdminBundle:Form:silex_form_div_layout.html.twig */
class __TwigTemplate_3e4e0fa6a0939e86e467090c0ceba484 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'choice_widget' => array($this, 'block_choice_widget'),
            'field_widget' => array($this, 'block_field_widget'),
            'form_label' => array($this, 'block_form_label'),
            'field_row' => array($this, 'block_field_row'),
            'field_errors' => array($this, 'block_field_errors'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 2
        echo "
";
        // line 3
        $this->displayBlock('choice_widget', $context, $blocks);
        // line 31
        echo "
";
        // line 32
        $this->displayBlock('field_widget', $context, $blocks);
        // line 38
        echo "
";
        // line 40
        echo "
";
        // line 41
        $this->displayBlock('form_label', $context, $blocks);
        // line 61
        echo "
";
        // line 63
        echo "
";
        // line 64
        $this->displayBlock('field_row', $context, $blocks);
        // line 75
        echo "
";
        // line 77
        echo "
";
        // line 78
        $this->displayBlock('field_errors', $context, $blocks);
    }

    // line 3
    public function block_choice_widget($context, array $blocks = array())
    {
        // line 4
        ob_start();
        // line 5
        echo "    ";
        if ($this->getContext($context, "expanded")) {
            // line 6
            echo "        <ul ";
            $this->displayBlock("widget_container_attributes", $context, $blocks);
            echo " class=\"inputs-list\">
        ";
            // line 7
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getContext($context, "form"));
            foreach ($context['_seq'] as $context["_key"] => $context["child"]) {
                // line 8
                echo "            <li>
                ";
                // line 9
                echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getContext($context, "child"), 'label', array("in_list_checkbox" => true, "widget" => $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getContext($context, "child"), 'widget')));
                echo "
            </li>
        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['child'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 12
            echo "        </ul>
    ";
        } else {
            // line 14
            echo "    <select ";
            $this->displayBlock("widget_attributes", $context, $blocks);
            if ($this->getContext($context, "multiple")) {
                echo " multiple=\"multiple\"";
            }
            echo ">
        ";
            // line 15
            if ((!(null === $this->getContext($context, "empty_value")))) {
                // line 16
                echo "            <option value=\"\">";
                echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans($this->getContext($context, "empty_value")), "html", null, true);
                echo "</option>
        ";
            }
            // line 18
            echo "        ";
            if ((twig_length_filter($this->env, $this->getContext($context, "preferred_choices")) > 0)) {
                // line 19
                echo "            ";
                $context["options"] = $this->getContext($context, "preferred_choices");
                // line 20
                echo "            ";
                $this->displayBlock("widget_choice_options", $context, $blocks);
                echo "
            ";
                // line 21
                if (((twig_length_filter($this->env, $this->getContext($context, "choices")) > 0) && (!(null === $this->getContext($context, "separator"))))) {
                    // line 22
                    echo "                <option disabled=\"disabled\">";
                    echo twig_escape_filter($this->env, $this->getContext($context, "separator"), "html", null, true);
                    echo "</option>
            ";
                }
                // line 24
                echo "        ";
            }
            // line 25
            echo "        ";
            $context["options"] = $this->getContext($context, "choices");
            // line 26
            echo "        ";
            $this->displayBlock("widget_choice_options", $context, $blocks);
            echo "
    </select>
    ";
        }
        echo trim(preg_replace('/>\s+</', '><', ob_get_clean()));
    }

    // line 32
    public function block_field_widget($context, array $blocks = array())
    {
        // line 33
        ob_start();
        // line 34
        echo "    ";
        $context["type"] = ((array_key_exists("type", $context)) ? (_twig_default_filter($this->getContext($context, "type"), "text")) : ("text"));
        // line 35
        echo "    <input type=\"";
        echo twig_escape_filter($this->env, $this->getContext($context, "type"), "html", null, true);
        echo "\" ";
        $this->displayBlock("widget_attributes", $context, $blocks);
        echo " value=\"";
        echo twig_escape_filter($this->env, $this->getContext($context, "value"), "html", null, true);
        echo "\" />
";
        echo trim(preg_replace('/>\s+</', '><', ob_get_clean()));
    }

    // line 41
    public function block_form_label($context, array $blocks = array())
    {
        // line 42
        ob_start();
        // line 43
        echo "    ";
        if ((!$this->getContext($context, "compound"))) {
            // line 44
            echo "        ";
            $context["attr"] = twig_array_merge($this->getContext($context, "attr"), array("for" => $this->getContext($context, "id")));
            // line 45
            echo "    ";
        }
        // line 46
        echo "    ";
        if ($this->getContext($context, "required")) {
            // line 47
            echo "        ";
            $context["attr"] = twig_array_merge($this->getContext($context, "attr"), array("class" => ((($this->getAttribute($this->getContext($context, "attr", true), "class", array(), "any", true, true)) ? (_twig_default_filter($this->getAttribute($this->getContext($context, "attr", true), "class"), "")) : ("")) . " required")));
            // line 48
            echo "    ";
        }
        // line 49
        echo "    ";
        if (((array_key_exists("in_list_checkbox", $context) && $this->getContext($context, "in_list_checkbox")) && array_key_exists("widget", $context))) {
            // line 50
            echo "        <label";
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getContext($context, "attr"));
            foreach ($context['_seq'] as $context["attrname"] => $context["attrvalue"]) {
                echo " ";
                echo twig_escape_filter($this->env, $this->getContext($context, "attrname"), "html", null, true);
                echo "=\"";
                echo twig_escape_filter($this->env, $this->getContext($context, "attrvalue"), "html", null, true);
                echo "\"";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['attrname'], $context['attrvalue'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            echo ">
            ";
            // line 51
            echo $this->getContext($context, "widget");
            echo "
            <span>
                ";
            // line 53
            echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans($this->getContext($context, "label")), "html", null, true);
            echo "
            </span>
        </label>
    ";
        } else {
            // line 57
            echo "        <label";
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getContext($context, "attr"));
            foreach ($context['_seq'] as $context["attrname"] => $context["attrvalue"]) {
                echo " ";
                echo twig_escape_filter($this->env, $this->getContext($context, "attrname"), "html", null, true);
                echo "=\"";
                echo twig_escape_filter($this->env, $this->getContext($context, "attrvalue"), "html", null, true);
                echo "\"";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['attrname'], $context['attrvalue'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            echo ">";
            echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans($this->getContext($context, "label")), "html", null, true);
            echo "</label>
    ";
        }
        echo trim(preg_replace('/>\s+</', '><', ob_get_clean()));
    }

    // line 64
    public function block_field_row($context, array $blocks = array())
    {
        // line 65
        ob_start();
        // line 66
        echo "    <div class=\"control-group ";
        echo (((0 < twig_length_filter($this->env, $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getContext($context, "form"), 'errors')))) ? ("error") : (""));
        echo " \">
        ";
        // line 67
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getContext($context, "form"), 'label', (twig_test_empty($_label_ = ((array_key_exists("label", $context)) ? (_twig_default_filter($this->getContext($context, "label"), null)) : (null))) ? array() : array("label" => $_label_)));
        echo "
        <div class=\"controls\">
            ";
        // line 69
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getContext($context, "form"), 'widget');
        echo "
            ";
        // line 70
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getContext($context, "form"), 'errors');
        echo "
        </div>
    </div>
";
        echo trim(preg_replace('/>\s+</', '><', ob_get_clean()));
    }

    // line 78
    public function block_field_errors($context, array $blocks = array())
    {
        // line 79
        ob_start();
        // line 80
        echo "    ";
        if ((twig_length_filter($this->env, $this->getContext($context, "errors")) > 0)) {
            // line 81
            echo "        ";
            if (((!$this->getAttribute($this->getContext($context, "form"), "hasParent")) || twig_in_filter("repeated", $this->getAttribute($this->getContext($context, "form"), "get", array(0 => "types"), "method")))) {
                // line 82
                echo "            <div class=\"control-group error\">
        ";
            }
            // line 84
            echo "        <span class=\"help-inline\">
            ";
            // line 85
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getContext($context, "errors"));
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
            foreach ($context['_seq'] as $context["_key"] => $context["error"]) {
                // line 86
                echo "                ";
                if ($this->getAttribute($this->getContext($context, "loop"), "first")) {
                    // line 87
                    echo "                    ";
                    echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans($this->getAttribute($this->getContext($context, "error"), "messageTemplate"), $this->getAttribute($this->getContext($context, "error"), "messageParameters"), "validators"), "html", null, true);
                    echo "
                ";
                }
                // line 89
                echo "            ";
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
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['error'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 90
            echo "        </span>
        ";
            // line 91
            if (((!$this->getAttribute($this->getContext($context, "form"), "hasParent")) || twig_in_filter("repeated", $this->getAttribute($this->getContext($context, "form"), "get", array(0 => "types"), "method")))) {
                // line 92
                echo "            </div>
        ";
            }
            // line 94
            echo "    ";
        }
        echo trim(preg_replace('/>\s+</', '><', ob_get_clean()));
    }

    public function getTemplateName()
    {
        return "SonataAdminBundle:Form:silex_form_div_layout.html.twig";
    }

    public function getDebugInfo()
    {
        return array (  335 => 94,  326 => 90,  306 => 87,  303 => 86,  283 => 84,  279 => 82,  276 => 81,  273 => 80,  271 => 79,  268 => 78,  259 => 70,  255 => 69,  245 => 66,  218 => 57,  211 => 53,  206 => 51,  190 => 50,  187 => 49,  169 => 43,  167 => 42,  164 => 41,  134 => 26,  77 => 8,  65 => 5,  56 => 78,  53 => 77,  686 => 206,  680 => 203,  677 => 202,  675 => 201,  669 => 198,  659 => 197,  654 => 195,  642 => 193,  639 => 192,  636 => 191,  627 => 185,  624 => 184,  607 => 182,  590 => 181,  585 => 179,  581 => 178,  578 => 177,  575 => 176,  572 => 175,  566 => 171,  562 => 169,  560 => 168,  555 => 167,  538 => 165,  521 => 164,  517 => 163,  512 => 162,  509 => 161,  506 => 160,  503 => 159,  500 => 158,  498 => 157,  495 => 156,  486 => 151,  482 => 149,  480 => 148,  477 => 147,  475 => 146,  472 => 145,  462 => 123,  453 => 120,  450 => 119,  437 => 138,  435 => 137,  432 => 136,  423 => 132,  421 => 131,  416 => 129,  405 => 127,  402 => 126,  400 => 119,  391 => 118,  377 => 111,  375 => 110,  371 => 109,  366 => 107,  356 => 105,  353 => 104,  343 => 98,  337 => 96,  331 => 92,  329 => 91,  324 => 92,  318 => 90,  312 => 89,  310 => 87,  302 => 86,  298 => 84,  289 => 81,  286 => 85,  274 => 77,  272 => 76,  269 => 75,  254 => 68,  250 => 67,  247 => 66,  243 => 65,  238 => 64,  236 => 63,  233 => 62,  208 => 57,  203 => 56,  200 => 55,  197 => 54,  175 => 45,  173 => 42,  112 => 19,  110 => 25,  90 => 18,  87 => 17,  69 => 190,  49 => 103,  23 => 3,  82 => 27,  62 => 156,  40 => 41,  20 => 1,  479 => 162,  473 => 161,  468 => 125,  460 => 155,  456 => 121,  452 => 151,  443 => 140,  439 => 148,  436 => 147,  434 => 146,  429 => 144,  426 => 133,  422 => 142,  412 => 134,  408 => 132,  406 => 131,  401 => 130,  397 => 129,  392 => 126,  386 => 115,  383 => 121,  380 => 112,  378 => 119,  373 => 116,  367 => 112,  364 => 111,  361 => 110,  359 => 106,  354 => 106,  340 => 97,  336 => 103,  321 => 91,  313 => 99,  311 => 98,  308 => 97,  304 => 95,  297 => 91,  293 => 90,  284 => 89,  282 => 79,  277 => 78,  267 => 85,  263 => 71,  257 => 81,  251 => 80,  246 => 78,  240 => 64,  234 => 74,  228 => 59,  223 => 58,  219 => 70,  213 => 69,  207 => 68,  198 => 67,  181 => 47,  176 => 65,  170 => 61,  168 => 60,  146 => 34,  142 => 56,  128 => 24,  125 => 44,  107 => 24,  38 => 6,  144 => 32,  141 => 51,  135 => 47,  126 => 45,  109 => 18,  103 => 16,  67 => 175,  61 => 13,  47 => 75,  105 => 24,  93 => 14,  76 => 13,  72 => 191,  68 => 6,  225 => 96,  216 => 90,  212 => 88,  205 => 84,  201 => 83,  196 => 80,  194 => 79,  191 => 50,  189 => 77,  186 => 76,  180 => 72,  172 => 44,  159 => 61,  154 => 59,  147 => 33,  132 => 48,  127 => 46,  121 => 29,  118 => 28,  114 => 42,  104 => 23,  100 => 34,  78 => 21,  75 => 23,  71 => 19,  58 => 18,  34 => 38,  27 => 3,  91 => 20,  84 => 16,  44 => 74,  25 => 3,  28 => 3,  24 => 2,  19 => 1,  94 => 39,  88 => 6,  79 => 14,  59 => 155,  31 => 4,  26 => 2,  21 => 2,  70 => 20,  63 => 4,  46 => 9,  22 => 1,  163 => 59,  155 => 58,  152 => 35,  149 => 34,  145 => 46,  139 => 31,  131 => 25,  123 => 30,  120 => 21,  115 => 20,  106 => 36,  101 => 15,  96 => 21,  83 => 18,  80 => 9,  74 => 16,  66 => 15,  55 => 12,  52 => 104,  50 => 75,  43 => 7,  41 => 7,  37 => 40,  35 => 5,  32 => 32,  29 => 31,  184 => 48,  178 => 46,  171 => 62,  165 => 58,  162 => 57,  157 => 41,  153 => 54,  151 => 53,  143 => 54,  138 => 51,  136 => 50,  133 => 43,  130 => 47,  122 => 22,  119 => 42,  116 => 35,  111 => 37,  108 => 37,  102 => 30,  98 => 21,  95 => 20,  92 => 19,  89 => 12,  85 => 25,  81 => 15,  73 => 7,  64 => 174,  60 => 3,  57 => 145,  54 => 144,  51 => 11,  48 => 64,  45 => 63,  42 => 61,  39 => 61,  36 => 5,  33 => 3,  30 => 1,);
    }
}
