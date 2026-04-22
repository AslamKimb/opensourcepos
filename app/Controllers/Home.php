<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;
use Throwable;

class Home extends Secure_Controller
{
    public function __construct()
    {
        parent::__construct('home', null, 'home');
    }

    /**
     * @return string
     */
    public function getIndex(): string
    {
        $data['dashboard'] = $this->buildDashboardData($this->global_view_data['allowed_modules'] ?? []);

        return view('home/home', $data);
    }

    private function buildDashboardData(array $allowedModules): array
    {
        $allowedModuleIds = $this->getAllowedModuleIds($allowedModules);
        $metrics          = [];

        if (in_array('sales', $allowedModuleIds, true)) {
            $metrics[] = $this->getTodaySalesSummary();
            $metrics[] = $this->getPendingOrdersSummary();
        }

        if (in_array('cashups', $allowedModuleIds, true)) {
            $metrics[] = $this->getCashupSummary();
        }

        if (in_array('items', $allowedModuleIds, true)) {
            $metrics[] = $this->getLowStockSummary();
        }

        if (in_array('receivings', $allowedModuleIds, true)) {
            $metrics[] = $this->getReceivingSummary();
        }

        return [
            'quick_actions'  => $this->getQuickActions($allowedModuleIds),
            'metrics'        => $metrics,
            'role_shortcuts' => array_slice($allowedModules, 0, 6),
            'recent_modules' => array_slice($allowedModules, 0, 4),
            'module_index'   => $this->getModuleIndex($allowedModules),
        ];
    }

    private function getAllowedModuleIds(array $allowedModules): array
    {
        return array_values(array_filter(array_map(static fn ($module) => $module->module_id ?? null, $allowedModules)));
    }

    private function getQuickActions(array $allowedModuleIds): array
    {
        $actions = [
            'sales' => [
                'label'  => lang('Common.dashboard_open_register'),
                'href'   => 'sales',
                'icon'   => 'glyphicon-shopping-cart',
                'module' => 'sales',
                'tone'   => 'primary',
            ],
            'items' => [
                'label'  => lang('Common.dashboard_manage_items'),
                'href'   => 'items',
                'icon'   => 'glyphicon-tags',
                'module' => 'items',
                'tone'   => 'secondary',
            ],
            'customers' => [
                'label'  => lang('Common.dashboard_manage_customers'),
                'href'   => 'customers',
                'icon'   => 'glyphicon-user',
                'module' => 'customers',
                'tone'   => 'secondary',
            ],
            'receivings' => [
                'label'  => lang('Common.dashboard_receive_stock'),
                'href'   => 'receivings',
                'icon'   => 'glyphicon-download-alt',
                'module' => 'receivings',
                'tone'   => 'secondary',
            ],
            'cashups' => [
                'label'  => lang('Common.dashboard_cashups'),
                'href'   => 'cashups',
                'icon'   => 'glyphicon-piggy-bank',
                'module' => 'cashups',
                'tone'   => 'secondary',
            ],
            'reports' => [
                'label'  => lang('Common.dashboard_view_reports'),
                'href'   => 'reports',
                'icon'   => 'glyphicon-stats',
                'module' => 'reports',
                'tone'   => 'secondary',
            ],
        ];

        $quickActions = [];

        foreach ($actions as $moduleId => $action) {
            if (in_array($moduleId, $allowedModuleIds, true)) {
                $quickActions[] = $action;
            }
        }

        return array_slice($quickActions, 0, 4);
    }

    private function getTodaySalesSummary(): array
    {
        try {
            $builder = db_connect()->table('sales AS sales');
            $builder->select('COUNT(DISTINCT sales.sale_id) AS count');
            $builder->select('COALESCE(SUM(sales_payments.payment_amount - sales_payments.cash_refund), 0) AS total', false);
            $builder->join('sales_payments AS sales_payments', 'sales_payments.sale_id = sales.sale_id', 'left');
            $builder->where('sales.sale_status', COMPLETED);
            $builder->where('sales.sale_time >=', date('Y-m-d 00:00:00'));
            $builder->where('sales.sale_time <=', date('Y-m-d 23:59:59'));
            $row = $builder->get()->getRow();

            return [
                'label' => lang('Common.dashboard_today_sales'),
                'value' => to_currency((string) ($row->total ?? 0)),
                'meta'  => lang('Common.dashboard_completed_sales', [(int) ($row->count ?? 0)]),
                'href'  => 'sales',
                'icon'  => 'glyphicon-shopping-cart',
                'tone'  => 'success',
            ];
        } catch (Throwable) {
            return $this->getUnavailableMetric(lang('Common.dashboard_today_sales'), 'sales', 'glyphicon-shopping-cart');
        }
    }

    private function getPendingOrdersSummary(): array
    {
        try {
            $builder = db_connect()->table('sales');
            $builder->where('sale_status', SUSPENDED);
            $count = $builder->countAllResults();

            return [
                'label' => lang('Common.dashboard_pending_orders'),
                'value' => (string) $count,
                'meta'  => lang('Common.dashboard_suspended_sales'),
                'href'  => 'sales',
                'icon'  => 'glyphicon-time',
                'tone'  => $count > 0 ? 'warning' : 'neutral',
            ];
        } catch (Throwable) {
            return $this->getUnavailableMetric(lang('Common.dashboard_pending_orders'), 'sales', 'glyphicon-time');
        }
    }

    private function getCashupSummary(): array
    {
        try {
            $builder = db_connect()->table('cash_up');
            $builder->where('deleted', 0);
            $builder->where('close_date', null);
            $openCashups = $builder->countAllResults();

            return [
                'label' => lang('Common.dashboard_cashup_status'),
                'value' => $openCashups > 0 ? lang('Common.dashboard_cashup_open') : lang('Common.dashboard_cashup_closed'),
                'meta'  => lang('Common.dashboard_open_cashups', [$openCashups]),
                'href'  => 'cashups',
                'icon'  => 'glyphicon-piggy-bank',
                'tone'  => $openCashups > 0 ? 'warning' : 'neutral',
            ];
        } catch (Throwable) {
            return $this->getUnavailableMetric(lang('Common.dashboard_cashup_status'), 'cashups', 'glyphicon-piggy-bank');
        }
    }

    private function getLowStockSummary(): array
    {
        try {
            $builder = db_connect()->table('items AS items');
            $builder->select('COUNT(DISTINCT items.item_id) AS count');
            $builder->join('item_quantities AS item_quantities', 'item_quantities.item_id = items.item_id', 'left');
            $builder->where('items.deleted', 0);
            $builder->where('items.reorder_level >', 0);
            $builder->where('COALESCE(item_quantities.quantity, 0) <= items.reorder_level', null, false);
            $row   = $builder->get()->getRow();
            $count = (int) ($row->count ?? 0);

            return [
                'label' => lang('Common.dashboard_low_stock'),
                'value' => (string) $count,
                'meta'  => $count > 0 ? lang('Common.dashboard_items_need_attention') : lang('Common.dashboard_stock_ok'),
                'href'  => 'items',
                'icon'  => 'glyphicon-alert',
                'tone'  => $count > 0 ? 'danger' : 'neutral',
            ];
        } catch (Throwable) {
            return $this->getUnavailableMetric(lang('Common.dashboard_low_stock'), 'items', 'glyphicon-alert');
        }
    }

    private function getReceivingSummary(): array
    {
        try {
            $builder = db_connect()->table('receivings');
            $builder->where('receiving_time >=', date('Y-m-d 00:00:00'));
            $builder->where('receiving_time <=', date('Y-m-d 23:59:59'));
            $count = $builder->countAllResults();

            return [
                'label' => lang('Common.dashboard_receivings'),
                'value' => (string) $count,
                'meta'  => lang('Common.dashboard_receivings_today'),
                'href'  => 'receivings',
                'icon'  => 'glyphicon-download-alt',
                'tone'  => 'neutral',
            ];
        } catch (Throwable) {
            return $this->getUnavailableMetric(lang('Common.dashboard_receivings'), 'receivings', 'glyphicon-download-alt');
        }
    }

    private function getUnavailableMetric(string $label, string $href, string $icon): array
    {
        return [
            'label' => $label,
            'value' => lang('Common.unknown'),
            'meta'  => lang('Common.dashboard_data_unavailable'),
            'href'  => $href,
            'icon'  => $icon,
            'tone'  => 'muted',
        ];
    }

    private function getModuleIndex(array $allowedModules): array
    {
        $moduleIndex = [];

        foreach ($allowedModules as $module) {
            if (! isset($module->module_id)) {
                continue;
            }

            $moduleIndex[] = [
                'id'      => $module->module_id,
                'label'   => lang("Module.{$module->module_id}"),
                'href'    => base_url($module->module_id),
                'iconUrl' => base_url("images/menubar/{$module->module_id}.svg"),
            ];
        }

        return $moduleIndex;
    }

    /**
     * Logs the currently logged in employee out of the system.  Used in app/Views/partial/header.php
     *
     * @return RedirectResponse
     * @noinspection PhpUnused
     */
    public function getLogout(): RedirectResponse
    {
        $this->employee->logout();
        return redirect()->to('login');
    }

    /**
     * Load "change employee password" form
     *
     * @return ResponseInterface|string
     * @noinspection PhpUnused
     */
    public function getChangePassword(int $employeeId = NEW_ENTRY)
    {
        $loggedInEmployee = $this->employee->get_logged_in_employee_info();
        $currentPersonId = $loggedInEmployee->person_id;

        $employeeId = $employeeId === NEW_ENTRY ? $currentPersonId : $employeeId;

        if (!$this->employee->isAdmin($currentPersonId) && $employeeId !== $currentPersonId) {
            return $this->response->setStatusCode(403)->setBody(lang('Employees.unauthorized_modify'));
        }

        $person_info = $this->employee->get_info($employeeId);
        foreach (get_object_vars($person_info) as $property => $value) {
            $person_info->$property = $value;
        }
        $data['person_info'] = $person_info;

        return view('home/form_change_password', $data);
    }

    /**
     * Change employee password
     *
     * @return ResponseInterface
     */
    public function postSave(int $employeeId = NEW_ENTRY): ResponseInterface
    {
        $currentUser = $this->employee->get_logged_in_employee_info();

        $employeeId = $employeeId === NEW_ENTRY ? $currentUser->person_id : $employeeId;

        if (!$this->employee->isAdmin($currentUser->person_id) && $employeeId !== $currentUser->person_id) {
            return $this->response->setStatusCode(403)->setJSON([
                'success' => false,
                'message' => lang('Employees.unauthorized_modify')
            ]);
        }

        if (!empty($this->request->getPost('current_password')) && $employeeId != NEW_ENTRY) {
            if ($this->employee->check_password($this->request->getPost('username', FILTER_SANITIZE_FULL_SPECIAL_CHARS), $this->request->getPost('current_password'))) {
                // Validate password length BEFORE hashing
                $new_password = $this->request->getPost('password');

                if (strlen($new_password) < 8) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => lang('Employees.password_minlength'),
                        'id'      => NEW_ENTRY
                    ]);
                }

                $employee_data = [
                    'username'     => $this->request->getPost('username', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                    'password'     => password_hash($new_password, PASSWORD_DEFAULT),
                    'hash_version' => 2
                ];

                if ($this->employee->change_password($employee_data, $employeeId)) {
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => lang('Employees.successful_change_password'),
                        'id'      => $employeeId
                    ]);
                } else {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => lang('Employees.unsuccessful_change_password'),
                        'id'      => NEW_ENTRY
                    ]);
                }
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => lang('Employees.current_password_invalid'),
                    'id'      => NEW_ENTRY
                ]);
            }
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => lang('Employees.current_password_invalid'),
                'id'      => NEW_ENTRY
            ]);
        }
    }
}
