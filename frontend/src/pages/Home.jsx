import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import axios from 'axios';
import { useAuth } from '../context/AuthContext';
import ParticleBackground from '../components/ParticleBackground';

const Home = () => {
  const { user } = useAuth();
  const [featured, setFeatured] = useState(null);
  const [genresData, setGenresData] = useState({});
  const [continueWatching, setContinueWatching] = useState([]);
  const [movies, setMovies] = useState([]);
  const [upcoming, setUpcoming] = useState([]);
  const [loading, setLoading] = useState(true);

  const genres = ['Adventure', 'Shounen', 'Romance', 'Seinen'];

  useEffect(() => {
    const fetchData = async () => {
      try {
        // 1. Fetch featured anime
        const featRes = await axios.get('/api/anime/featured');
        setFeatured(featRes.data);

        // 2. Fetch anime by genres (parallel)
        const genrePromises = genres.map(async (genre) => {
          const res = await axios.get(`/api/anime/by-genre?genre=${genre}`);
          return { genre, data: res.data };
        });
        const genreResults = await Promise.all(genrePromises);
        const genresMap = {};
        genreResults.forEach(r => {
          genresMap[r.genre] = r.data;
        });
        setGenresData(genresMap);

        // 3. Fetch continue watching (if logged in)
        if (user && user.user_id) {
          const cwRes = await axios.get(`/api/history/continue/${user.user_id}`);
          setContinueWatching(cwRes.data);
        }

        // 4. Fetch movies and upcoming
        const moviesRes = await axios.get('/api/anime/movies');
        setMovies(moviesRes.data);

        const upcomingRes = await axios.get('/api/anime/upcoming');
        setUpcoming(upcomingRes.data);

      } catch (err) {
        console.error('Error fetching home dashboard data:', err);
      } finally {
        setLoading(false);
      }
    };

    fetchData();
  }, [user]);

  if (loading) {
    return (
      <div className="min-h-screen flex items-center justify-center bg-gradient-main text-gold text-lg font-bold">
        Loading dashboard...
      </div>
    );
  }

  return (
    <div className="pb-16 px-4 md:px-8">
      {/* Featured Anime Hero Section */}
      {featured && (
        <section className="relative w-full h-[55vh] min-h-[400px] rounded-2xl overflow-hidden mb-12 border border-gold/15 shadow-lg bg-black flex flex-col md:flex-row items-center reveal">
          <ParticleBackground />
          <div className="absolute inset-0 bg-gradient-hero z-10 pointer-events-none md:bg-gradient-to-r md:from-black md:via-black/70 md:to-transparent"></div>
          
          <div className="relative w-full md:w-[40%] h-[220px] md:h-full overflow-hidden flex items-center justify-center">
            <img 
              src={`/assets/thumbnails/${featured.anime_image}`} 
              alt={featured.anime_name} 
              className="w-full h-full object-cover object-center"
            />
          </div>

          <div className="relative w-full md:w-[60%] p-6 md:p-10 z-20 flex flex-col gap-4 self-start md:self-center">
            <h1 className="text-2xl md:text-4xl font-extrabold text-white font-primary leading-tight">{featured.anime_name}</h1>
            <div className="text-gray-light text-sm md:text-[0.95rem] flex flex-col gap-1 max-w-xl">
              <span><strong>Genre:</strong> {featured.genre}</span>
              <span><strong>Type:</strong> {featured.anime_type}</span>
              {featured.description && (
                <p className="mt-2 text-gray-light/85 line-clamp-3 md:line-clamp-4">{featured.description}</p>
              )}
            </div>
            
            <Link 
              to={`/player?anime_id=${featured.anime_id}`} 
              className="w-max bg-gradient-gold text-black font-bold px-6 py-3 rounded hover:opacity-90 hover:scale-[1.02] active:scale-[0.98] transition-all duration-200 shadow-gold hover:shadow-gold-lg mt-2 text-center"
            >
              Watch Now
            </Link>
          </div>
        </section>
      )}

      {/* Main categories listing */}
      <h2 className="section-title text-center text-3xl font-extrabold mb-8 background-clip-text text-transparent bg-gradient-gold uppercase tracking-wider reveal">
        Anime Genres
      </h2>

      <section className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        {genres.map((genre) => (
          <div key={genre} className="genre-box flowing-gradient-box rounded-xl p-5 md:p-6 reveal">
            <h2 className="text-xl font-bold text-white border-l-4 border-pink-500 pl-3 mb-5 font-primary">{genre}</h2>
            
            {genresData[genre] && genresData[genre].length > 0 ? (
              <div className="flex flex-col gap-3.5 stagger-children">
                {genresData[genre].map((anime) => (
                  <Link 
                    key={anime.anime_id}
                    to={`/player?anime_id=${anime.anime_id}`} 
                    className="anime-item group flex items-center gap-3 p-2 rounded-lg bg-black/25 hover:bg-black/50 border border-white/5 hover:border-cyan-500/35 transition-all duration-300 shadow-sm"
                  >
                    <div className="w-14 h-20 rounded overflow-hidden flex-shrink-0 border border-white/10">
                      <img 
                        src={`/assets/thumbnails/${anime.anime_image}`} 
                        alt={anime.anime_name} 
                        className="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                      />
                    </div>
                    <div className="flex-grow min-w-0">
                      <h3 className="text-white font-bold text-[0.88rem] truncate group-hover:text-cyan-400 transition-colors duration-200">{anime.anime_name}</h3>
                      <p className="text-[0.78rem] text-gray-400 mt-1 flex items-center gap-1.5">
                        <span>{anime.anime_type}</span>
                        <span className="text-pink-500 font-bold">•</span>
                        <span>Ep: {anime.episode_count || 'N/A'}</span>
                      </p>
                    </div>
                  </Link>
                ))}
              </div>
            ) : (
              <p className="text-gray-light italic text-xs">No anime found.</p>
            )}
          </div>
        ))}
      </section>

      {/* Continue Watching Section */}
      {continueWatching && continueWatching.length > 0 && (
        <section className="mt-12 reveal">
          <h2 className="section-title text-center text-2xl font-extrabold mb-8 background-clip-text text-transparent bg-gradient-gold uppercase tracking-wider">
            Continue Watching
          </h2>
          <div className="bg-white/3 border border-white/5 rounded-xl p-5 md:p-6">
            <div className="anime-grid grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-5 stagger-children">
              {continueWatching.map((cw) => (
                <Link 
                  key={cw.anime_id}
                  to={`/player?anime_id=${cw.anime_id}&episode_id=${cw.last_episode_id}`} 
                  className="anime-item group relative rounded-lg overflow-hidden border border-transparent hover:border-gold/20 bg-black/40 hover:shadow-gold transition-all duration-300"
                >
                  <div className="w-full aspect-[2/3] md:h-[340px] overflow-hidden">
                    <img 
                      src={`/assets/thumbnails/${cw.anime_image}`} 
                      alt={cw.anime_name} 
                      className="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                    />
                  </div>
                  <div className="absolute inset-0 bg-gradient-to-t from-black via-black/60 to-transparent flex flex-col justify-end p-4 translate-y-[60px] group-hover:translate-y-0 transition-transform duration-300">
                    <div className="text-white font-bold text-sm truncate mb-1">{cw.anime_name}</div>
                    <div className="flex justify-between items-center text-xs text-gold">
                      <span>Episode {cw.last_episode_number}</span>
                      <span className="text-gray-light">{cw.anime_type}</span>
                    </div>
                  </div>
                </Link>
              ))}
            </div>
          </div>
        </section>
      )}

      {/* Movies Section */}
      <section className="mt-12 reveal">
        <h2 className="section-title text-center text-2xl font-extrabold mb-8 background-clip-text text-transparent bg-gradient-gold uppercase tracking-wider">
          Movies
        </h2>
        <div className="bg-white/3 border border-white/5 rounded-xl p-5 md:p-6">
          {movies && movies.length > 0 ? (
            <div className="anime-grid grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-5 stagger-children">
              {movies.map((movie) => (
                <Link 
                  key={movie.anime_id}
                  to={`/player?anime_id=${movie.anime_id}`} 
                  className="anime-item group relative rounded-lg overflow-hidden border border-transparent hover:border-gold/20 bg-black/40 hover:shadow-gold transition-all duration-300"
                >
                  <div className="w-full aspect-[2/3] md:h-[340px] overflow-hidden">
                    <img 
                      src={`/assets/thumbnails/${movie.anime_image}`} 
                      alt={movie.anime_name} 
                      className="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                    />
                  </div>
                  <div className="absolute inset-0 bg-gradient-to-t from-black via-black/60 to-transparent flex flex-col justify-end p-4 translate-y-[60px] group-hover:translate-y-0 transition-transform duration-300">
                    <div className="text-white font-bold text-sm truncate mb-1">{movie.anime_name}</div>
                    <div className="text-xs text-gray-light">{movie.anime_type}</div>
                  </div>
                </Link>
              ))}
            </div>
          ) : (
            <p className="text-gray-light italic text-sm">No movies found.</p>
          )}
        </div>
      </section>

      {/* Upcoming Section */}
      <section className="mt-12 reveal">
        <h2 className="section-title text-center text-2xl font-extrabold mb-8 background-clip-text text-transparent bg-gradient-gold uppercase tracking-wider">
          Upcoming
        </h2>
        <div className="bg-white/3 border border-white/5 rounded-xl p-5 md:p-6">
          {upcoming && upcoming.length > 0 ? (
            <div className="anime-grid grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-5 stagger-children">
              {upcoming.map((anime) => (
                <Link 
                  key={anime.anime_id}
                  to={`/player?anime_id=${anime.anime_id}`} 
                  className="anime-item group relative rounded-lg overflow-hidden border border-transparent hover:border-gold/20 bg-black/40 hover:shadow-gold transition-all duration-300"
                >
                  <div className="w-full aspect-[2/3] md:h-[340px] overflow-hidden">
                    <img 
                      src={`/assets/thumbnails/${anime.anime_image}`} 
                      alt={anime.anime_name} 
                      className="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                    />
                  </div>
                  <div className="absolute inset-0 bg-gradient-to-t from-black via-black/60 to-transparent flex flex-col justify-end p-4 translate-y-[60px] group-hover:translate-y-0 transition-transform duration-300">
                    <div className="text-white font-bold text-sm truncate mb-1">{anime.anime_name}</div>
                    <div className="text-xs text-gray-light">{anime.anime_type}</div>
                  </div>
                </Link>
              ))}
            </div>
          ) : (
            <p className="text-gray-light italic text-sm">No upcoming anime found.</p>
          )}
        </div>
      </section>
    </div>
  );
};

export default Home;
