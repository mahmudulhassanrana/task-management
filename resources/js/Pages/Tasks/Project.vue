<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import axios from 'axios';
import draggable from 'vuedraggable';

const props = defineProps({ projects: Array })

const localProjects = ref([...props.projects ?? []]);

const form = ref({
  id: null,
  name: '',
  priority: null,
});

const errors = ref({});

const isLoading = ref(false);

const submit = async () => {
  errors.value = {};
  isLoading.value = true;
  try {
    await axios.get('/sanctum/csrf-cookie');
    if (form.value.id) {
      await axios.post(`/project-update/${form.value.id}`, form.value);
      toast.success("Project updated successfully!", {
        autoClose: 3000,
        position: "bottom-right",
        theme: "dark",
      });
    } else {
      await axios.post('/project', form.value);
      toast.success("Project created successfully!", {
        autoClose: 3000,
        position: "bottom-right",
        theme: "dark",
      });
    }
    closeModalAndReset();
    router.visit('/project');
  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = error.response.data.errors;
    } else {
      console.error('Unexpected error:', error);
    }
  } finally {
    isLoading.value = false;
  }
};

const editProject = (project) => {
  form.value = {
    id: project.id,
    name: project.name,
    priority: project.priority,
  };
  const modal = new bootstrap.Modal(document.getElementById('addProjectModal'));
  modal.show();
};

const deleteProject = async (id) => {
  if (confirm('Are you sure you want to delete this project?')) {
    isLoading.value = true;
    try {
      await axios.get('/sanctum/csrf-cookie');
      await axios.post(`/project-delete/${id}`);
      toast.success("Project deleted successfully!", {
        autoClose: 3000,
        position: "bottom-right",
        theme: "dark",
      });
      router.visit('/project');
    } catch (error) {
      console.error('Delete failed:', error);
    } finally {
      isLoading.value = false;
    }
  }
};

const onDragEnd = async () => {
  const orderedIds = localProjects.value.map(project => project.id);

  try {
    await axios.get('/sanctum/csrf-cookie');
    await axios.post('/project-reorder', orderedIds);
    toast.success("Projects reordered successfully!", {
      autoClose: 2000,
      position: "bottom-right",
      theme: "dark",
    });
    router.visit('/project');
  } catch (error) {
    console.error('Reordering failed:', error);
  }
};

const closeModalAndReset = () => {
  const modal = bootstrap.Modal.getInstance(document.getElementById('addProjectModal'));
  modal?.hide();

  form.value = {
    id: null,
    name: '',
    priority: null,
  };
  errors.value = {};
};

const logout = async () => {
  try {
    await axios.post('/logout');
    router.visit('/');
  } catch (error) {
    console.error('Logout failed:', error);
  }
};

const task = async () => {
    router.visit('/tasks');
};

</script>



<template>
    <div class="container py-4">
      <!-- Header -->
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">📋 Task Management</h2>
        <button class="btn btn-outline-primary" @click="task" :disabled="isLoading">
           Go To Task Management
        </button>
        <button class="btn btn-outline-danger" @click="logout" :disabled="isLoading">
          <i class="bi bi-box-arrow-right me-1"></i> Logout
        </button>
      </div>

      <!-- Add Project Button -->
      <div class="text-end mb-3">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addProjectModal" :disabled="isLoading">
          <i class="bi bi-plus-circle me-1"></i> Add Project
        </button>
      </div>

      <!-- Project List -->
      <div v-if="localProjects && localProjects.length" class="list-group">
        <draggable v-model="localProjects" @end="onDragEnd" item-key="id">
          <template #item="{ element: project }">
            <div class="list-group-item border-start-4 border-primary py-3" :key="project.id">
              <div class="d-flex justify-content-between">
                <div>
                  <h5 class="mb-1">
                    <i class="bi bi-check2-square me-2 text-primary"></i> {{ project.name }}
                  </h5>
                </div>
                <div class="btn-group align-self-start">
                  <button class="btn btn-sm btn-outline-primary" @click="editProject(project)" :disabled="isLoading">
                    <i class="bi bi-pencil"></i>
                  </button>
                  <button class="btn btn-sm btn-outline-danger" @click="deleteProject(project.id)" :disabled="isLoading">
                    <i class="bi bi-trash"></i>
                  </button>
                </div>
              </div>
            </div>
          </template>
        </draggable>
      </div>

      <p v-else class="text-muted text-center mt-5">
        <i class="bi bi-inbox me-2"></i>No projects found.
      </p>

      <!-- Add/Edit Project Modal -->
      <div class="modal fade" id="addProjectModal" tabindex="-1" aria-labelledby="addProjectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <form @submit.prevent="submit" class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="addProjectModalLabel">{{ form.id ? 'Edit Project' : 'Add Project' }}</h5>
              <button type="button" class="btn-close" @click="closeModalAndReset" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <!-- Project Name -->
              <div class="mb-3">
                <label class="form-label">Project Name <span class="text-danger">*</span></label>
                <input v-model="form.name" class="form-control" />
                <div v-if="errors.name" class="text-danger mt-1">{{ errors.name[0] }}</div>
              </div>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" @click="closeModalAndReset" data-bs-dismiss="modal" :disabled="isLoading">Cancel</button>
              <button type="submit" class="btn btn-primary" :disabled="isLoading">
                {{ form.id ? 'Update Project' : 'Add Project' }}
              </button>
            </div>
          </form>
        </div>
      </div>

    </div>
  </template>


