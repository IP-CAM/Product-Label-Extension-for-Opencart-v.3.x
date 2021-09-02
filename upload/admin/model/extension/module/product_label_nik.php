<?php
class ModelExtensionModuleProductLabelNik extends Model {
    public function install() {
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "label` (
              `label_id` INT(11) NOT NULL AUTO_INCREMENT,
              `image` VARCHAR(255) NOT NULL,
              `font_size` INT(11) NOT NULL,
              `font_color` VARCHAR(25) NOT NULL,
              `bg_color` VARCHAR(25) NOT NULL,
              `padding` VARCHAR(100) NOT NULL,
              `border_radius` INT(11) NOT NULL,
              `position_left` VARCHAR(25) NOT NULL,
              `position_left_unit` VARCHAR(25) NOT NULL,
              `position_top` VARCHAR(25) NOT NULL,
              `position_top_unit` VARCHAR(25) NOT NULL,
              `position_right` VARCHAR(25) NOT NULL,
              `position_right_unit` VARCHAR(25) NOT NULL,
              `position_bottom` VARCHAR(25) NOT NULL,
              `position_bottom_unit` VARCHAR(25) NOT NULL,
              `status` TINYINT(1) NOT NULL DEFAULT 1,
              `date_added` datetime NOT NULL,
              PRIMARY KEY (`label_id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
		");
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "label_description` (
              `id` INT(11) NOT NULL AUTO_INCREMENT,
              `label_id` INT(11) NOT NULL,
              `language_id` INT(11) NOT NULL,
              `text` VARCHAR(255) NOT NULL,
              PRIMARY KEY (`id`, `language_id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
		");
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "product_label` (
              `product_label_id` INT(11) NOT NULL AUTO_INCREMENT,
              `product_id` INT(11) NOT NULL,
              `label_id` INT(11) NOT NULL,
              PRIMARY KEY (`product_label_id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
		");
    }

    public function uninstall() {
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "label`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "label_description`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "product_label`");
    }

    public function addProductLabel($data) {
        $this->db->query("INSERT INTO `" . DB_PREFIX . "label` SET `font_size` = '" . (int)$data['font_size'] . "', `font_color` = '" . $this->db->escape($data['font_color']) . "', `bg_color` = '" . $this->db->escape($data['bg_color']) . "', `padding` = '" . $this->db->escape($data['padding']) . "', `border_radius` = '" . (int)$data['border_radius'] . "', `position_left` = '" . $this->db->escape($data['position_left']) . "', `position_left_unit` = '" . $this->db->escape($data['position_left_unit']) . "', `position_top` = '" . $this->db->escape($data['position_top']) . "', `position_top_unit` = '" . $this->db->escape($data['position_top_unit']) . "', `position_right` = '" . $this->db->escape($data['position_right']) . "', `position_right_unit` = '" . $this->db->escape($data['position_right_unit']) . "', `position_bottom` = '" . $this->db->escape($data['position_bottom']) . "', `position_bottom_unit` = '" . $this->db->escape($data['position_bottom_unit']) . "', `status` = '" . (int)$data['status'] . "', date_added = NOW()");

        $label_id = $this->db->getLastId();

        if (isset($data['image'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "label SET `image` = '" . $this->db->escape($data['image']) . "' WHERE label_id = '" . (int)$label_id . "'");
        }

        foreach ($data['label_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "label_description SET label_id = '" . (int)$label_id . "', language_id = '" . (int)$language_id . "', `text` = '" . $this->db->escape($value['text']) . "'");
        }

        return $label_id;
    }

    public function editProductLabel($label_id, $data) {
        $this->db->query("UPDATE `" . DB_PREFIX . "label` SET `font_size` = '" . (int)$data['font_size'] . "', `font_color` = '" . $this->db->escape($data['font_color']) . "', `bg_color` = '" . $this->db->escape($data['bg_color']) . "', `padding` = '" . $this->db->escape($data['padding']) . "', `border_radius` = '" . (int)$data['border_radius'] . "', `position_left` = '" . $this->db->escape($data['position_left']) . "', `position_left_unit` = '" . $this->db->escape($data['position_left_unit']) . "', `position_top` = '" . $this->db->escape($data['position_top']) . "', `position_top_unit` = '" . $this->db->escape($data['position_top_unit']) . "', `position_right` = '" . $this->db->escape($data['position_right']) . "', `position_right_unit` = '" . $this->db->escape($data['position_right_unit']) . "', `position_bottom` = '" . $this->db->escape($data['position_bottom']) . "', `position_bottom_unit` = '" . $this->db->escape($data['position_bottom_unit']) . "', `status` = '" . (int)$data['status'] . "' WHERE `label_id` = '" . (int)$label_id . "'");

        if (isset($data['image'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "label SET `image` = '" . $this->db->escape($data['image']) . "' WHERE label_id = '" . (int)$label_id . "'");
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "label_description WHERE label_id = '" . (int)$label_id . "'");

        foreach ($data['label_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "label_description SET label_id = '" . (int)$label_id . "', language_id = '" . (int)$language_id . "', `text` = '" . $this->db->escape($value['text']) . "'");
        }
    }

    public function deleteProductLabel($label_id) {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "label` WHERE `label_id` = '" . (int)$label_id . "'");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "label_description` WHERE `label_id` = '" . (int)$label_id . "'");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "product_label` WHERE `label_id` = '" . (int)$label_id . "'");
    }

    public function getLabel($label_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "label WHERE label_id = '" . (int)$label_id . "'");

        return $query->row;
    }

    public function getLabelDescription($label_id) {
        $label_description_data = array();
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "label_description WHERE label_id = '" . (int)$label_id . "'");

        foreach ($query->rows as $result) {
            $label_description_data[$result['language_id']] = array(
                'text' => $result['text'],
            );
        }

        return $label_description_data;
    }

    public function getLabelInfo($label_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "label l LEFT JOIN `" . DB_PREFIX . "label_description` ld ON (l.label_id = ld.label_id) WHERE ld.language_id = '" . (int)$this->config->get('config_language_id') . "' AND l.label_id = '" . (int)$label_id . "'");

        return $query->row;
    }

    public function getLabels($data = array()) {
        $sql = "SELECT * FROM `" . DB_PREFIX . "label` l LEFT JOIN `" . DB_PREFIX . "label_description` ld ON (l.label_id = ld.label_id) WHERE ld.language_id = '" . (int)$this->config->get('config_language_id') . "'";

        $sort_data = array(
            'ld.text',
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY ld.text";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getProductLabels($product_id) {
        $product_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_label pl LEFT JOIN " . DB_PREFIX . "label l ON (pl.label_id = l.label_id) WHERE pl.product_id = '" . (int)$product_id . "' AND l.status = '1' ORDER BY pl.product_label_id ASC");

        foreach ($query->rows as $result) {
            $product_data[$result['label_id']] = $this->getLabelInfo($result['label_id']);
        }

        return $product_data;
    }
}
