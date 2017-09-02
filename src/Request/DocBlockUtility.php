<?php

namespace JasonRoman\NbaApi\Request;

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class DocBlockUtility
{
    const REGEX_SPLIT_BY_NEWLINE        = "/\\r\\n|\\r|\\n/";
    const REGEX_LINE_HAS_CONTENT        = '/^(?=\s+?\*[^\/])(.+)/';
    const REGEX_REMOVE_LEADING_ASTERISK = '/^(\*\s+?)/';
    const REGEX_VAR_PATTERN             = '/@var (\w+)\b/';

    // 'tags' to skip when returning the description (if the line starts with the tag, skip it)
    const DESCRIPTION_SKIP_TAGS = [
        '@',
        ')',
        '})', // end of a multi-line annotation
    ];

    /**
     * @var AnnotationReader
     */
    protected $reader;

    public function __construct()
    {
        $this->reader = new AnnotationReader();
    }

    public function getPropertyAnnotations($reflectionProperty)
    {
        return $this->reader->getPropertyAnnotations($reflectionProperty);
    }

    public function getDescription(string $docComment)
    {
        $description = [];

        // split at each line
        foreach (preg_split(self::REGEX_SPLIT_BY_NEWLINE, $docComment) as $line) {
            // if line does not start with asterisk, or only contains an asterisk, do nothing
            if (!preg_match(self::REGEX_LINE_HAS_CONTENT, $line, $matches)) {
                continue;
            }

            // trim whitespace and remove leading asterisk
            $info = trim(preg_replace(self::REGEX_REMOVE_LEADING_ASTERISK, '', trim($matches[1])));

            // add to description if info not start with a tag marked for skipping (annotation, phpDoc tag)
            $addInfo = true;

            foreach (self::DESCRIPTION_SKIP_TAGS as $skipTag) {
                if (substr($info, 0, strlen($skipTag)) === $skipTag) {
                    $addInfo = false;
                }
            }

            if ($addInfo) {
                $description[] = $info;
            }
        }

        return implode("\n", $description);
    }

    public function getConstraints($reflectionProperty)
    {
        $constraints = [];

        $annotations = $this->reader->getPropertyAnnotations($reflectionProperty);

        foreach ($annotations as $annotation) {
            $constraints[] = $annotation;
        }

        return $constraints;
    }

    public function getConstraint($reflectionProperty, $constraintClass, $getFromAll = true)
    {
        if (
            ($constraint = $this->reader->getPropertyAnnotation($reflectionProperty, $constraintClass)) &&
            $constraint instanceof Constraint
        ) {
            return $constraint;
        }

        if (!$getFromAll) {
            return;
        }

        if (
            ($allConstraint = $this->getConstraintFromAll($reflectionProperty, $constraintClass)) &&
            $allConstraint instanceof Constraint
        ) {
            return $allConstraint;
        }
    }

    public function hasConstraint($reflectionProperty, $constraintClass)
    {
        return (bool) $this->getConstraint($reflectionProperty, $constraintClass);
    }

    public function getRequiredConstraints($reflectionProperty)
    {
        $constraints = [];

        $annotations = $this->reader->getPropertyAnnotations($reflectionProperty);

        foreach ($annotations as $annotation) {
            if ($annotation instanceof NotBlank || $annotation instanceof NotNull) {
                $constraints[] = $annotation;
            }
        }

        return $constraints;
    }

    public function getConstraintFromAll($reflectionProperty, $constraintClass)
    {
        if (!($allConstraint = $this->getConstraint($reflectionProperty, All::class, false))) {
            return;
        }

        foreach ($allConstraint->constraints as $constraint) {
            if ($constraint instanceof $constraintClass) {
                return $constraint;
            }
        }
    }

    public function getVar(string $docComment)
    {
        preg_match(self::REGEX_VAR_PATTERN, $docComment, $matches);

        if ($matches) {
            return $matches[1];
        }

        return '';
    }
}