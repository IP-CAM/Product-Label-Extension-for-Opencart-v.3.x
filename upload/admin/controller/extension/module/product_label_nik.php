<?php
class ControllerExtensionModuleProductLabelNik extends Controller {
	private $error = array();

    public function index() {
        $this->load->language('extension/module/product_label_nik');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('extension/module/product_label_nik');

        $this->getList();
    }

    public function add() {
        $this->load->language('extension/module/product_label_nik');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('extension/module/product_label_nik');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_extension_module_product_label_nik->addProductLabel($this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            $this->response->redirect($this->url->link('extension/module/product_label_nik', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

        $this->getForm();
    }

    public function edit() {
        $this->load->language('extension/module/product_label_nik');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('extension/module/product_label_nik');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_extension_module_product_label_nik->editProductLabel($this->request->get['label_id'], $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            $this->response->redirect($this->url->link('extension/module/product_label_nik', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

        $this->getForm();
    }

    public function delete() {
        $this->load->language('extension/module/product_label_nik');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('extension/module/product_label_nik');

        if (isset($this->request->get['label_id']) && $this->validate()) {
            $this->model_extension_module_product_label_nik->deleteProductLabel($this->request->get['label_id']);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            $this->response->redirect($this->url->link('extension/module/product_label_nik', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

        $this->getList();
    }

	protected function getList() {
        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'ld.text';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module/product_label_nik', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['action'] = $this->url->link('extension/module/product_label_nik', 'user_token=' . $this->session->data['user_token'], true);

        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

        $data['addLabel'] = $this->url->link('extension/module/product_label_nik/add', 'user_token=' . $this->session->data['user_token'], true);

        $url = '';

        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        $data['sort_label_text'] = $this->url->link('extension/module/product_label_nik', 'user_token=' . $this->session->data['user_token'] . '&sort=ld.text' . $url, true);

        $filter_data = array(
            'sort'  => $sort,
            'order' => $order,
        );

        $results = $this->model_extension_module_product_label_nik->getLabels($filter_data);

        $this->load->model('tool/image');

        foreach ($results as $result) {
            $image = $result['image'] ? $this->model_tool_image->resize($result['image'], 25, 25) : $this->model_tool_image->resize('no_image.png', 25, 25);

            $data['labels'][] = array(
                'label_id' => $result['label_id'],
                'text'     => $result['text'],
                'image'    => $image,
                'status'   => $result['status'],
                'edit'     => $this->url->link('extension/module/product_label_nik/edit', 'user_token=' . $this->session->data['user_token'] . '&label_id=' . $result['label_id'], true),
                'delete'   => $this->url->link('extension/module/product_label_nik/delete', 'user_token=' . $this->session->data['user_token'] . '&label_id=' . $result['label_id'], true)
            );
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/product_label_nik', $data));
    }

	protected function getForm() {
        $data['text_form'] = !isset($this->request->get['label_id']) ? $this->language->get('text_add_label') : $this->language->get('text_edit_label');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['text'])) {
            $data['error_text'] = $this->error['text'];
        } else {
            $data['error_text'] = array();
        }

        if (isset($this->error['image'])) {
            $data['error_image'] = $this->error['image'];
        } else {
            $data['error_image'] = array();
        }

        $url = '';

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module/product_label_nik', 'user_token=' . $this->session->data['user_token'] . $url, true)
        );

        $data['cancel'] = $this->url->link('extension/module/product_label_nik', 'user_token=' . $this->session->data['user_token'] . $url, true);

        if (isset($this->request->get['label_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $label_info = $this->model_extension_module_product_label_nik->getLabel($this->request->get['label_id']);
            $label_description = $this->model_extension_module_product_label_nik->getLabelDescription($this->request->get['label_id']);
        }

        if (isset($this->request->get['label_id'])) {
            $data['action'] = $this->url->link('extension/module/product_label_nik/edit', 'user_token=' . $this->session->data['user_token'] . '&label_id=' . $this->request->get['label_id'] . $url, true);
        } else {
            $data['action'] = $this->url->link('extension/module/product_label_nik/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
        }

        $data['user_token'] = $this->session->data['user_token'];

        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();

        if (isset($this->request->post['label_description'])) {
            $data['label_description'] = $this->request->post['label_description'];
        } elseif (!empty($label_description)) {
            $data['label_description'] = $label_description;
        } else {
            $data['label_description'] = array();
        }

        $this->load->model('tool/image');

        foreach ($data['label_description'] as $language_id => $label_description) {
            if ($label_description['image']) {
                $data['label_description'][$language_id]['thumb'] = $this->model_tool_image->resize($label_description['image'], 100, 100);
            } else {
                $data['label_description'][$language_id]['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
            }
        }

        $data['img_placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

        if (isset($this->request->post['font_size'])) {
            $data['font_size'] = $this->request->post['font_size'];
        } elseif (!empty($label_info)) {
            $data['font_size'] = $label_info['font_size'];
        } else {
            $data['font_size'] = '';
        }

        if (isset($this->request->post['font_color'])) {
            $data['font_color'] = $this->request->post['font_color'];
        } elseif (!empty($label_info)) {
            $data['font_color'] = $label_info['font_color'];
        } else {
            $data['font_color'] = '';
        }

        if (isset($this->request->post['border_radius'])) {
            $data['border_radius'] = $this->request->post['border_radius'];
        } elseif (!empty($label_info)) {
            $data['border_radius'] = $label_info['border_radius'];
        } else {
            $data['border_radius'] = '';
        }

        if (isset($this->request->post['bg_color'])) {
            $data['bg_color'] = $this->request->post['bg_color'];
        } elseif (!empty($label_info)) {
            $data['bg_color'] = $label_info['bg_color'];
        } else {
            $data['bg_color'] = '';
        }

        if (isset($this->request->post['padding'])) {
            $data['padding'] = $this->request->post['padding'];
        } elseif (!empty($label_info)) {
            $data['padding'] = $label_info['padding'];
        } else {
            $data['padding'] = '';
        }

        if (isset($this->request->post['position_left'])) {
            $data['position_left'] = $this->request->post['position_left'];
        } elseif (!empty($label_info)) {
            $data['position_left'] = $label_info['position_left'];
        } else {
            $data['position_left'] = '';
        }

        if (isset($this->request->post['position_left_unit'])) {
            $data['position_left_unit'] = $this->request->post['position_left_unit'];
        } elseif (!empty($label_info)) {
            $data['position_left_unit'] = $label_info['position_left_unit'];
        } else {
            $data['position_left_unit'] = 'px';
        }

        if (isset($this->request->post['position_top'])) {
            $data['position_top'] = $this->request->post['position_top'];
        } elseif (!empty($label_info)) {
            $data['position_top'] = $label_info['position_top'];
        } else {
            $data['position_top'] = '';
        }

        if (isset($this->request->post['position_top_unit'])) {
            $data['position_top_unit'] = $this->request->post['position_top_unit'];
        } elseif (!empty($label_info)) {
            $data['position_top_unit'] = $label_info['position_top_unit'];
        } else {
            $data['position_top_unit'] = 'px';
        }

        if (isset($this->request->post['position_right'])) {
            $data['position_right'] = $this->request->post['position_right'];
        } elseif (!empty($label_info)) {
            $data['position_right'] = $label_info['position_right'];
        } else {
            $data['position_right'] = '';
        }

        if (isset($this->request->post['position_right_unit'])) {
            $data['position_right_unit'] = $this->request->post['position_right_unit'];
        } elseif (!empty($label_info)) {
            $data['position_right_unit'] = $label_info['position_right_unit'];
        } else {
            $data['position_right_unit'] = 'px';
        }

        if (isset($this->request->post['position_bottom'])) {
            $data['position_bottom'] = $this->request->post['position_bottom'];
        } elseif (!empty($label_info)) {
            $data['position_bottom'] = $label_info['position_bottom'];
        } else {
            $data['position_bottom'] = '';
        }

        if (isset($this->request->post['position_bottom_unit'])) {
            $data['position_bottom_unit'] = $this->request->post['position_bottom_unit'];
        } elseif (!empty($label_info)) {
            $data['position_bottom_unit'] = $label_info['position_bottom_unit'];
        } else {
            $data['position_bottom_unit'] = 'px';
        }

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($label_info)) {
            $data['status'] = $label_info['status'];
        } else {
            $data['status'] = true;
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/product_label_form_nik', $data));
    }

    public function install() {
        if ($this->user->hasPermission('modify', 'extension/module/product_label_nik')) {
            $this->load->model('extension/module/product_label_nik');

            $this->model_extension_module_product_label_nik->install();
        }
    }

    public function uninstall() {
        if ($this->user->hasPermission('modify', 'extension/module/product_label_nik')) {
            $this->load->model('extension/module/product_label_nik');

            $this->model_extension_module_product_label_nik->uninstall();
        }
    }

    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'extension/module/product_label_nik')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        foreach ($this->request->post['label_description'] as $language_id => $value) {
            if ( ((utf8_strlen($value['text']) < 1) || (utf8_strlen($value['text']) > 255)) && (utf8_strlen($value['image']) < 1) ) {
                $this->error['text'][$language_id] = $this->language->get('error_text');
                $this->error['image'][$language_id] = $this->language->get('error_image');
            }
        }

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        return !$this->error;
    }

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/product_label_nik')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}