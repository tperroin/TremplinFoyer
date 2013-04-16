<?php

/* TperroinTremplinFoyerBundle:Helper:twitterButton.html.twig */
class __TwigTemplate_21815d9f5a3873b6e58f57716447b9fa extends Twig_Template
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
        if (((!array_key_exists("url", $context)) || ($this->getContext($context, "url") == null))) {
            // line 2
            echo "    ";
            $context["url"] = $this->getAttribute($this->getAttribute($this->getContext($context, "app"), "request"), "uri");
        }
        // line 4
        echo " 
";
        // line 5
        ob_start();
        // line 6
        echo " <a href=\"https://twitter.com/share\" class=\"twitter-share-button\"
 
   data-text=\"";
        // line 8
        echo twig_escape_filter($this->env, $this->getContext($context, "message"), "html", null, true);
        echo "\" 
   data-url=\"";
        // line 9
        echo twig_escape_filter($this->env, $this->getContext($context, "url"), "html", null, true);
        echo "\"
   data-lang=\"";
        // line 10
        echo twig_escape_filter($this->env, $this->getContext($context, "locale"), "html", null, true);
        echo "\"
 
   ";
        // line 12
        if ((!($this->getContext($context, "via") === false))) {
            // line 13
            echo "     data-via=\"";
            echo twig_escape_filter($this->env, $this->getContext($context, "via"), "html", null, true);
            echo "\"
   ";
        }
        // line 15
        echo " 
   ";
        // line 16
        if ((!($this->getContext($context, "tag") === false))) {
            // line 17
            echo "     data-hashtags=\"";
            echo twig_escape_filter($this->env, $this->getContext($context, "tag"), "html", null, true);
            echo "\"
   ";
        }
        // line 19
        echo " >";
        echo twig_escape_filter($this->env, $this->getContext($context, "text"), "html", null, true);
        echo "</a>
 
 <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=\"//platform.twitter.com/widgets.js\";fjs.parentNode.insertBefore(js,fjs);}}(document,\"script\",\"twitter-wjs\");</script>
";
        echo trim(preg_replace('/>\s+</', '><', ob_get_clean()));
    }

    public function getTemplateName()
    {
        return "TperroinTremplinFoyerBundle:Helper:twitterButton.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  66 => 19,  60 => 17,  58 => 16,  55 => 15,  49 => 13,  38 => 9,  30 => 6,  28 => 5,  25 => 4,  21 => 2,  53 => 14,  47 => 12,  45 => 11,  42 => 10,  36 => 8,  34 => 8,  31 => 6,  24 => 3,  22 => 2,  19 => 1,);
    }
}
