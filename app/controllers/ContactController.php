<?php
require_once __DIR__ . '/../models/ContactModel.php';

class ContactController extends Controller
{
    protected $model;

    public function __construct()
    {
        $this->model = new ContactModel();
    }

    public function index()
    {
        header('Location: ?url=info/index');
        exit;
    }

    public function store()
    {
        $data = $_POST ?? [];
        if (!empty($data['id'])) {
            $this->model->update($data['id'], $data);
        } else {
            $this->model->create($data);
        }
        header('Location: ?url=info/index');
        exit;
    }

    public function delete()
    {
        $id = $_GET['id'] ?? $_POST['id'] ?? null;
        if ($id) {
            $this->model->delete($id);
        }
        header('Location: ?url=info/index');
        exit;
    }

    public function find()
    {
        $id = $_GET['id'] ?? null;
        header('Content-Type: application/json');
        if ($id) {
            $row = $this->model->find($id);
            echo json_encode(['success' => (bool)$row, 'row' => $row]);
            return;
        }
        echo json_encode(['success' => false]);
    }
}
