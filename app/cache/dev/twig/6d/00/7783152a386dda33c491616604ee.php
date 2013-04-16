<?php

/* TperroinTremplinFoyerBundle:Helper:socialButtons.html.twig */
class __TwigTemplate_6d007783152a386dda33c491616604ee extends Twig_Template
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
        // line 1
        echo "<div class=\"large-7 social\" id=\"socialBar\">
  ";
        // line 2
        if ((!($this->getContext($context, "facebook") === false))) {
            // line 3
            echo "      ";
            echo $this->env->getExtension('acme_social_bar')->getFacebookLikeButton($this->getContext($context, "facebook"));
            echo "
    
  ";
        }
        // line 6
        echo " 
  ";
        // line 7
        if ((!($this->getContext($context, "googleplus") === false))) {
            // line 8
            echo "      ";
            echo $this->env->getExtension('acme_social_bar')->getGooglePlusButton($this->getContext($context, "googleplus"));
            echo "
  ";
        }
        // line 10
        echo " 
  ";
        // line 11
        if ((!($this->getContext($context, "twitter") === false))) {
            // line 12
            echo "      ";
            echo $this->env->getExtension('acme_social_bar')->getTwitterButton($this->getContext($context, "twitter"));
            echo "
  ";
        }
        // line 14
        echo "</div>";
    }

    public function getTemplateName()
    {
        return "TperroinTremplinFoyerBundle:Helper:socialButtons.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  53 => 14,  47 => 12,  45 => 11,  42 => 10,  36 => 8,  34 => 7,  31 => 6,  24 => 3,  22 => 2,  19 => 1,);
    }
}
