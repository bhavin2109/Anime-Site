import React, { useState, useEffect } from 'react';
import { useLocation, Link, useNavigate } from 'react-router-dom';
import axios from 'axios';
import { useAuth } from '../context/AuthContext';

const Player = () => {
  const { user } = useAuth();
  const location = useLocation();
  const navigate = useNavigate();

  const query = new URLSearchParams(location.search);
  const animeId = parseInt(query.get('anime_id') || '1');
  const episodeIdParam = query.get('episode_id');

  const [episodes, setEpisodes] = useState([]);
  const [currentEpisode, setCurrentEpisode] = useState(null);
  const [animeDetails, setAnimeDetails] = useState(null);
  const [continueWatching, setContinueWatching] = useState([]);
  const [watchlistMessage, setWatchlistMessage] = useState('');
  const [loading, setLoading] = useState(true);

  // Fetch episodes and anime details
  useEffect(() => {
    const fetchPlayerData = async () => {
      setLoading(true);
      setWatchlistMessage('');
      try {
        // Fetch anime info
        const animeRes = await axios.get(`/api/anime/${animeId}`);
        setAnimeDetails(animeRes.data);

        // Fetch list of episodes
        const episodesRes = await axios.get(`/api/episodes/by-anime/${animeId}`);
        const episodeList = episodesRes.data;
        setEpisodes(episodeList);

        if (episodeList.length > 0) {
          // Resolve current episode
          let selectedEp = null;
          if (episodeIdParam) {
            selectedEp = episodeList.find(e => e.episode_id === parseInt(episodeIdParam));
          }
          // Default to first episode if not specified or not found
          if (!selectedEp) {
            selectedEp = episodeList[0];
          }
          setCurrentEpisode(selectedEp);

          // Log watch history if user logged in
          if (user?.user_id && selectedEp) {
            await axios.post('/api/history', {
              user_id: user.user_id,
              anime_id: animeId,
              episode_id: selectedEp.episode_id
            });
          }
        } else {
          setCurrentEpisode(null);
        }

        // Fetch continue watching row
        if (user?.user_id) {
          const cwRes = await axios.get(`/api/history/continue/${user.user_id}`);
          setContinueWatching(cwRes.data);
        }

      } catch (err) {
        console.error('Error loading player data:', err);
      } finally {
        setLoading(false);
      }
    };

    fetchPlayerData();
  }, [animeId, episodeIdParam, user]);

  const handleAddToWatchlist = async () => {
    if (!user) {
      navigate('/login');
      return;
    }

    try {
      const res = await axios.post('/api/watchlist', {
        user_id: user.user_id,
        anime_id: animeId,
        status: 'Watching'
      });
      setWatchlistMessage(res.data.message);
    } catch (err) {
      console.error('Error adding to watchlist:', err);
    }
  };

  const getEpisodeNumber = (epId) => {
    const idx = episodes.findIndex(e => e.episode_id === epId);
    return idx !== -1 ? idx + 1 : 1;
  };

  if (loading) {
    return (
      <div className="min-h-screen flex items-center justify-center bg-gradient-main text-gold text-lg font-bold">
        Loading video player...
      </div>
    );
  }

  return (
    <div className="pb-16 px-4 md:px-8">
      {/* Player Split Panel */}
      <div className="max-w-7xl mx-auto flex flex-col lg:flex-row gap-6 mt-6 bg-gradient-card border border-gold/15 rounded-xl shadow-lg overflow-hidden backdrop-blur-glass reveal">
        
        {/* Sidebar: Episode Numbers */}
        <div className="w-full lg:w-[240px] bg-[#1a1e24]/80 p-5 border-b lg:border-b-0 lg:border-r border-gold/15 flex flex-col gap-4">
          <h3 className="text-white text-lg font-bold font-primary border-b border-gold/25 pb-2">List of Episodes</h3>
          <ul className="grid grid-cols-5 lg:grid-cols-4 gap-2.5 max-h-[300px] lg:max-h-[500px] overflow-y-auto pr-1">
            {episodes.map((ep, idx) => {
              const isActive = currentEpisode && currentEpisode.episode_id === ep.episode_id;
              return (
                <li key={ep.episode_id}>
                  <Link
                    to={`/player?anime_id=${animeId}&episode_id=${ep.episode_id}`}
                    className={`w-full aspect-square flex items-center justify-center font-bold text-sm rounded transition-all ${
                      isActive 
                        ? 'bg-gradient-gold text-black shadow-gold' 
                        : 'bg-white/5 border border-white/5 text-gray-light hover:border-gold/30 hover:text-white'
                    }`}
                  >
                    {idx + 1}
                  </Link>
                </li>
              );
            })}
            {episodes.length === 0 && (
              <p className="text-gray-light text-sm italic col-span-full">No episodes listed.</p>
            )}
          </ul>
        </div>

        {/* Main Section: Iframe Video Player */}
        <div className="flex-1 flex flex-col">
          <div className="relative w-full aspect-video bg-black flex items-center justify-center overflow-hidden">
            {currentEpisode && currentEpisode.episode_url ? (
              <iframe 
                src={`https://drive.google.com/file/d/${currentEpisode.episode_url}/preview`} 
                width="100%" 
                height="100%" 
                allow="autoplay" 
                className="absolute inset-0 w-full h-full border-none"
                allowFullScreen
                title="video-player"
              ></iframe>
            ) : (
              <p className="text-gray-light italic text-base">No video found for this episode.</p>
            )}
          </div>

          {/* Anime Meta Header Box */}
          {animeDetails && (
            <div className="p-6 flex flex-col sm:flex-row gap-5 border-t border-gold/15 items-start">
              <img 
                src={`/assets/thumbnails/${animeDetails.anime_image}`} 
                alt="" 
                className="w-24 sm:w-28 rounded object-cover shadow-md border border-white/10"
              />
              <div className="flex-grow flex flex-col gap-2">
                <h2 className="text-xl sm:text-2xl font-bold text-white font-primary">{animeDetails.anime_name}</h2>
                <div className="flex flex-wrap gap-x-4 gap-y-1 text-xs sm:text-sm text-gray-light">
                  <span><strong>Type:</strong> {animeDetails.anime_type}</span>
                  <span><strong>Genre:</strong> {animeDetails.genre}</span>
                  {currentEpisode && (
                    <span className="text-gold font-semibold">
                      Current Episode: {getEpisodeNumber(currentEpisode.episode_id)}
                    </span>
                  )}
                </div>
                
                <div className="flex items-center gap-4 mt-2">
                  <button 
                    onClick={handleAddToWatchlist}
                    className="bg-gradient-gold text-black text-sm font-bold px-4 py-2 rounded hover:opacity-90 transition-all cursor-pointer shadow-gold"
                  >
                    + Watchlist
                  </button>
                  {watchlistMessage && (
                    <span className="text-sm font-medium">{watchlistMessage}</span>
                  )}
                </div>
              </div>
            </div>
          )}
        </div>
      </div>

      {/* Continue Watching Section */}
      {user && continueWatching && continueWatching.length > 0 && (
        <div className="max-w-7xl mx-auto mt-12 reveal">
          <h2 className="text-xl sm:text-2xl font-bold text-white mb-6 border-l-4 border-gold pl-3 font-primary">Continue Watching</h2>
          <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-5 stagger-children">
            {continueWatching.map((cw) => (
              <Link 
                key={cw.anime_id}
                to={`/player?anime_id=${cw.anime_id}&episode_id=${cw.last_episode_id}`} 
                className="group relative rounded-lg overflow-hidden border border-transparent hover:border-gold/20 bg-black/40 hover:shadow-gold transition-all duration-300"
              >
                <div className="w-full h-[200px] overflow-hidden">
                  <img 
                    src={`/assets/thumbnails/${cw.anime_image}`} 
                    alt="" 
                    className="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                  />
                </div>
                <div className="absolute inset-0 bg-gradient-to-t from-black via-black/60 to-transparent flex flex-col justify-end p-3 translate-y-[45px] group-hover:translate-y-0 transition-transform duration-300">
                  <div className="text-white font-bold text-xs truncate mb-0.5">{cw.anime_name}</div>
                  <div className="flex justify-between items-center text-[10px] text-gold">
                    <span>Episode {cw.last_episode_number}</span>
                    <span className="text-gray-light">{cw.anime_type}</span>
                  </div>
                </div>
              </Link>
            ))}
          </div>
        </div>
      )}
    </div>
  );
};

export default Player;
