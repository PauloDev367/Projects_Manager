import { API_URL } from "@/constants";
import axios from "axios";

const token = localStorage.getItem('token');

export function getAllProjects() {
    return axios.get(`${API_URL}projects`, {
        headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json',
        }
    });
}
export function createProject(title) {
    const data = {
        title, position: 0
    }
    return axios.post(`${API_URL}projects`, data, {
        headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json',
        }
    });
}