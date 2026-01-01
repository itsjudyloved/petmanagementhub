import { useState } from 'react';
import axios from 'axios';

const API_BASE_URL = '/api';

export function usePetApi() {
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  const getAllPets = async () => {
    try {
      setLoading(true);
      setError(null);
      const response = await axios.get(`${API_BASE_URL}/pets`);
      return response.data;
    } catch (err) {
      setError(err.response?.data?.error || 'Failed to fetch pets');
      throw err;
    } finally {
      setLoading(false);
    }
  };

  const getPet = async (id) => {
    try {
      setLoading(true);
      setError(null);
      const response = await axios.get(`${API_BASE_URL}/pets/${id}`);
      return response.data;
    } catch (err) {
      setError(err.response?.data?.error || 'Failed to fetch pet');
      throw err;
    } finally {
      setLoading(false);
    }
  };

  const addPet = async (petData) => {
    try {
      setLoading(true);
      setError(null);
      const response = await axios.post(`${API_BASE_URL}/pets`, petData);
      return response.data;
    } catch (err) {
      setError(err.response?.data?.error || 'Failed to add pet');
      throw err;
    } finally {
      setLoading(false);
    }
  };

  const updatePet = async (id, petData) => {
    try {
      setLoading(true);
      setError(null);
      const response = await axios.put(`${API_BASE_URL}/pets/${id}`, petData);
      return response.data;
    } catch (err) {
      setError(err.response?.data?.error || 'Failed to update pet');
      throw err;
    } finally {
      setLoading(false);
    }
  };

  const deletePet = async (id) => {
    try {
      setLoading(true);
      setError(null);
      const response = await axios.delete(`${API_BASE_URL}/pets/${id}`);
      return response.data;
    } catch (err) {
      setError(err.response?.data?.error || 'Failed to delete pet');
      throw err;
    } finally {
      setLoading(false);
    }
  };

  return {
    loading,
    error,
    getAllPets,
    getPet,
    addPet,
    updatePet,
    deletePet
  };
} 