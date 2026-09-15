// src/services/api.js
import axios from 'axios'

export const api = axios.create({
  baseURL: 'http://10.10.10.20:8000/api', // Prefix API backend
  headers: { 'Content-Type': 'application/json' }
})