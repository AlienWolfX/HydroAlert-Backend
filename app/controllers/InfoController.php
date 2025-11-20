<?php
require_once __DIR__ . '/../models/EvacuationCenterModel.php';

class InfoController extends Controller
{
    protected $model;

    public function __construct()
    {
        $this->model = new EvacuationCenterModel();
    }

    public function index()
    {
        $centers = $this->model->all();
        $user = $_SESSION['user'] ?? null;
        $this->render('information', ['centers' => $centers, 'user' => $user]);
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

    public function toggle()
    {
        $id = $_POST['id'] ?? null;
        $status = $_POST['status'] ?? 'inactive';
        if ($id) {
            $row = $this->model->update($id, ['status' => $status]);
            header('Content-Type: application/json');
            echo json_encode(['success' => (bool)$row, 'row' => $row]);
            exit;
        }
        header('Content-Type: application/json');
        echo json_encode(['success' => false]);
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
