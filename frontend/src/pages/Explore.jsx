import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import axios from 'axios';

const Explore = () => {
  const [genresData, setGenresData] = useState({});
  const [upcomingMovies, setUpcomingMovies] = useState([]);
  const [loading, setLoading] = useState(true);

  const genres = ['Action', 'Shounen', 'Romance', 'Fantasy'];

  useEffect(() => {
    const fetchData = async () => {
      try {
        // Fetch movies by genre
        const genrePromises = genres.map(async (genre) => {
          const res = await axios.get(`/api/anime/movies-by-genre?genre=${genre}`);
          return { genre, data: res.data };
        });
        const genreResults = await Promise.all(genrePromises);
        const genresMap = {};
        genreResults.forEach(r => {
          genresMap[r.genre] = r.data;
        });
        setGenresData(genresMap);

        // Fetch upcoming movies
        const upcomingRes = await axios.get('/api/anime/upcoming-movies');
        setUpcomingMovies(upcomingRes.data);

      } catch (err) {
        console.error('Error fetching explore page data:', err);
      } finally {
        setLoading(false);
      }
    };

    fetchData();
  }, []);

  if (loading) {
    return (
      <div className="min-h-screen flex items-center justify-center bg-gradient-main text-gold text-lg font-bold">
        Loading movies...
      </div>
    );
  }

  return (
    <div className="pb-16 px-4 md:px-8">
      <h2 className="section-title text-center text-3xl font-extrabold my-8 background-clip-text text-transparent bg-gradient-gold uppercase tracking-wider reveal">
        Explore Movies
      </h2>

      <section className="flex flex-col gap-10">
        {genres.map((genre) => (
          <div key={genre} className="genre-box bg-white/3 border border-white/5 rounded-xl p-5 md:p-6 reveal">
            <h2 className="text-xl md:text-2xl font-bold text-white border-l-4 border-gold pl-3 mb-6 font-primary">{genre}</h2>
            
            {genresData[genre] && genresData[genre].length > 0 ? (
              <div className="anime-grid grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-5 stagger-children">
                {genresData[genre].map((anime) => (
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
                      <div className="flex justify-between items-center text-xs text-gray-light">
                        <span>Ep: {anime.episode_count || 'N/A'}</span>
                        <span>{anime.anime_type}</span>
                      </div>
                    </div>
                  </Link>
                ))}
              </div>
            ) : (
              <p className="text-gray-light italic text-sm">No movies found in this genre.</p>
            )}
          </div>
        ))}
      </section>

      {/* Upcoming Movies Section */}
      <section className="mt-12 reveal">
        <h2 className="section-title text-center text-2xl font-extrabold mb-8 background-clip-text text-transparent bg-gradient-gold uppercase tracking-wider">
          Upcoming Movies
        </h2>
        <div className="bg-white/3 border border-white/5 rounded-xl p-5 md:p-6">
          {upcomingMovies && upcomingMovies.length > 0 ? (
            <div className="anime-grid grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-5 stagger-children">
              {upcomingMovies.map((movie) => (
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
            <p className="text-gray-light italic text-sm">No upcoming movies found.</p>
          )}
        </div>
      </section>
    </div>
  );
};

export default Explore;
