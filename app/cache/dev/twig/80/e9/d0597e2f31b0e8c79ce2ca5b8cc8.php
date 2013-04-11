<?php

/* SonataAdminBundle:CRUD:base_show_field.html.twig */
class __TwigTemplate_80e9d0597e2f31b0e8c79ce2ca5b8cc8 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'name' => array($this, 'block_name'),
            'field' => array($this, 'block_field'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 11
        echo "
<th>";
        // line 12
        $this->displayBlock('name', $context, $blocks);
        echo "</th>
<td>";
        // line 13
        $this->displayBlock('field', $context, $blocks);
        echo "</td>
";
    }

    // line 12
    public function block_name($context, array $blocks = array())
    {
        echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "admin"), "trans", array(0 => $this->getAttribute($this->getContext($context, "field_description"), "label")), "method"), "html", null, true);
    }

    // line 13
    public function block_field($context, array $blocks = array())
    {
        if ($this->getAttribute($this->getAttribute($this->getContext($context, "field_description"), "options"), "safe")) {
            echo $this->getContext($context, "value");
        } else {
            echo nl2br(twig_escape_filter($this->env, $this->getContext($context, "value"), "html", null, true));
        }
    }

    public function getTemplateName()
    {
        return "SonataAdminBundle:CRUD:base_show_field.html.twig";
    }

    public function getDebugInfo()
    {
        return array (  40 => 13,  24 => 12,  21 => 11,  147 => 58,  140 => 56,  133 => 54,  127 => 52,  125 => 51,  122 => 50,  118 => 49,  115 => 48,  108 => 44,  104 => 42,  102 => 41,  99 => 40,  95 => 39,  92 => 38,  89 => 37,  83 => 35,  78 => 32,  73 => 30,  67 => 28,  64 => 27,  61 => 26,  56 => 24,  50 => 22,  47 => 21,  42 => 19,  36 => 17,  34 => 12,  31 => 15,  28 => 13,);
    }
}
