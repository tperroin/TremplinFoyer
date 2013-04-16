<?php

/* TperroinTremplinFoyerBundle:Association:show.html.twig */
class __TwigTemplate_efe564b97487238fd9c8c3ebb2f5c76e extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("::base.html.twig");

        $this->blocks = array(
            'corps' => array($this, 'block_corps'),
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

    // line 2
    public function block_corps($context, array $blocks = array())
    {
        // line 3
        echo "    <div class=\"row\">
        <div class=\"large-12\">
            <h1 class=\"bleu\">/// L'association</h1>
        </div>
    </div>

    <div class=\"row\">
        <div class=\"large-12\">
            <div class=\"row blocBlanc\">
                <h5><a class=\"bleu\" href=\"";
        // line 12
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("association_show", array("id" => $this->getAttribute($this->getContext($context, "entity"), "id"))), "html", null, true);
        echo "\">";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "entity"), "titre"), "html", null, true);
        echo "</a></h5>
                <div>";
        // line 13
        echo $this->env->getExtension('acme_social_bar')->getSocialButtons(array("googleplus" => false, "twitter" => false));
        echo "</div>
                <div class=\"large-12\">
                    ";
        // line 15
        echo $this->env->getExtension('sonata_media')->media($this->getAttribute($this->getContext($context, "entity"), "image"), "big", array());
        // line 16
        echo "                </div>

                <div class=\"large-12\">
                    <div class=\"row\"></div>
                        <p>";
        // line 20
        echo $this->getAttribute($this->getContext($context, "entity"), "contenu");
        echo "</p>
                </div>
            </div>
                
        </div>
    </div>
";
    }

    public function getTemplateName()
    {
        return "TperroinTremplinFoyerBundle:Association:show.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  61 => 20,  55 => 16,  53 => 15,  48 => 13,  42 => 12,  31 => 3,  28 => 2,);
    }
}
