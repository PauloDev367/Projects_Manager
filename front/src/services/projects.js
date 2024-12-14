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
