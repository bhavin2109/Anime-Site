import React, { useState, useEffect } from 'react';
import axios from 'axios';

const AdminUsersList = () => {
  const [users, setUsers] = useState([]);
  const [loading, setLoading] = useState(true);

  const fetchUsers = async () => {
    try {
      const res = await axios.get('/api/auth/users');
      setUsers(res.data);
    } catch (err) {
      console.error('Error fetching admin users list:', err);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchUsers();
  }, []);

  const handleDelete = async (id, name) => {
    if (name === 'admin') {
      alert("Cannot delete the admin account!");
      return;
    }
    if (!confirm(`Are you sure you want to delete user "${name}"?`)) return;
    try {
      await axios.delete(`/api/auth/users/${id}`);
      alert('User removed successfully!');
      fetchUsers();
    } catch (err) {
      console.error('Failed to remove user:', err);
      alert('Error removing user.');
    }
  };

  const getProfilePicUrl = (row) => {
    if (row.profile_picture) {
      return `/assets/profile_pics/${row.profile_picture}`;
    }
    return `https://ui-avatars.com/api/?name=${encodeURIComponent(row.username)}&background=ccaa00&color=090c11&size=128`;
  };

  if (loading) return <div className="text-gold font-bold text-center py-10">Loading users...</div>;

  return (
    <div className="flex flex-col gap-6 w-full max-w-4xl">
      <div className="border-b border-white/5 pb-3">
        <h2 className="text-2xl font-bold text-white font-primary font-bold">Registered Users</h2>
      </div>

      <div className="overflow-x-auto bg-gradient-card border border-gold/15 rounded-xl shadow-lg">
        <table className="w-full text-left border-collapse">
          <thead>
            <tr className="border-b border-gold/15 text-gray-light text-xs uppercase tracking-wider bg-white/2">
              <th className="py-3 px-4 text-center">User ID</th>
              <th className="py-3 px-4">Avatar</th>
              <th className="py-3 px-4">Username</th>
              <th className="py-3 px-4">Email</th>
              <th className="py-3 px-4 text-center">Date of Birth</th>
              <th className="py-3 px-4 text-center">Actions</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-white/5 text-[0.9rem]">
            {users.map((row) => (
              <tr key={row.user_id} className="hover:bg-white/2 transition-colors">
                <td className="py-4 px-4 text-center text-gray-light font-mono">{row.user_id}</td>
                <td className="py-4 px-4 text-center">
                  <img 
                    src={getProfilePicUrl(row)} 
                    alt="" 
                    className="w-10 h-10 rounded-full object-cover border border-white/10 mx-auto"
                  />
                </td>
                <td className="py-4 px-4 font-semibold text-white">{row.username}</td>
                <td className="py-4 px-4 text-gray-light">{row.email}</td>
                <td className="py-4 px-4 text-center text-gray-light">
                  {row.date_of_birth ? row.date_of_birth.substring(0, 10) : 'N/A'}
                </td>
                <td className="py-4 px-4 text-center">
                  <button 
                    disabled={row.username === 'admin'}
                    onClick={() => handleDelete(row.user_id, row.username)}
                    className="bg-red-500/10 hover:bg-red-500/25 border border-red-500/30 text-red-400 hover:text-red-300 text-xs font-semibold px-2.5 py-1.5 rounded transition-all cursor-pointer disabled:opacity-30 disabled:hover:bg-red-500/10 disabled:cursor-not-allowed"
                  >
                    Remove User
                  </button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
};

export default AdminUsersList;
