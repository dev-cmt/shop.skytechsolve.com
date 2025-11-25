<?php
// app/Services/PageBuilder.php
namespace App\Services;

use App\Models\Page;
use App\Models\PageSection;

class PageBuilder
{
    protected $sectionTypes = [
        'hero' => [
            'name' => 'Hero Section',
            'description' => 'Large banner with title, subtitle and call-to-action buttons',
            'icon' => 'star',
            'fields' => [
                'title' => 'text',
                'subtitle' => 'textarea',
                'background_image' => 'image',
                'buttons' => 'repeater'
            ]
        ],
        'features' => [
            'name' => 'Features Grid',
            'description' => 'Showcase features or services in a grid layout',
            'icon' => 'th-large',
            'fields' => [
                'title' => 'text',
                'description' => 'textarea',
                'features' => 'repeater'
            ]
        ],
        'products' => [
            'name' => 'Products Showcase',
            'description' => 'Display products in various layouts',
            'icon' => 'shopping-bag',
            'fields' => [
                'title' => 'text',
                'description' => 'textarea',
                'layout' => 'select:grid-4,grid-3,grid-2',
                'limit' => 'number',
                'featured_only' => 'checkbox',
                'product_ids' => 'product-selector'
            ]
        ],
        'contact' => [
            'name' => 'Contact Form',
            'description' => 'Contact form with company information',
            'icon' => 'envelope',
            'fields' => [
                'title' => 'text',
                'description' => 'textarea',
                'contact_info' => 'repeater'
            ]
        ],
        'text' => [
            'name' => 'Text Content',
            'description' => 'Rich text content with formatting options',
            'icon' => 'paragraph',
            'fields' => [
                'title' => 'text',
                'content' => 'wysiwyg',
                'alignment' => 'select:left,center,right'
            ]
        ],
        'image' => [
            'name' => 'Image Banner',
            'description' => 'Full-width image with optional text overlay',
            'icon' => 'image',
            'fields' => [
                'image' => 'image',
                'alt_text' => 'text',
                'caption' => 'textarea',
                'alignment' => 'select:left,center,right'
            ]
        ],
        'cta' => [
            'name' => 'Call to Action',
            'description' => 'Prominent section to drive user action',
            'icon' => 'bullhorn',
            'fields' => [
                'title' => 'text',
                'subtitle' => 'textarea',
                'background_color' => 'color',
                'buttons' => 'repeater'
            ]
        ],
        'testimonials' => [
            'name' => 'Testimonials',
            'description' => 'Customer reviews and testimonials',
            'icon' => 'comments',
            'fields' => [
                'title' => 'text',
                'testimonials' => 'repeater'
            ]
        ],
        'team' => [
            'name' => 'Team Members',
            'description' => 'Showcase your team members',
            'icon' => 'users',
            'fields' => [
                'title' => 'text',
                'description' => 'textarea',
                'members' => 'repeater'
            ]
        ]
    ];

    public function getSectionTypes()
    {
        return $this->sectionTypes;
    }

    public function getSectionIcon($type)
    {
        return $this->sectionTypes[$type]['icon'] ?? 'cube';
    }

    public function renderSection(Section $section)
    {
        $view = "page-builder.sections.{$section->type}";

        if (!view()->exists($view)) {
            $view = 'page-builder.sections.default';
        }

        try {
            return view($view, [
                'section' => $section,
                'content' => $section->content ?? [],
                'settings' => $section->settings ?? []
            ]);
        } catch (\Exception $e) {
            logger()->error('Section rendering error: ' . $e->getMessage());
            return view('page-builder.sections.default', [
                'section' => $section,
                'content' => $section->content ?? [],
                'error' => $e->getMessage()
            ]);
        }
    }

    public function getFieldConfig($type)
    {
        return $this->sectionTypes[$type]['fields'] ?? [];
    }

    public function validateSectionData($type, $data)
    {
        $requiredFields = array_filter($this->sectionTypes[$type]['fields'] ?? [], function($fieldType) {
            return in_array($fieldType, ['text', 'textarea', 'wysiwyg']);
        });

        foreach (array_keys($requiredFields) as $field) {
            if (empty($data[$field])) {
                return false;
            }
        }

        return true;
    }
}
