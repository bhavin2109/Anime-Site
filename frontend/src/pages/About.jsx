import React from 'react';

const About = () => {
  const team = [
    { name: 'Bhavin', role: 'Lead Developer', initial: 'B' },
    { name: 'Team Member', role: 'Backend Developer', initial: 'A' },
    { name: 'Team Member', role: 'UI/UX Designer', initial: 'C' },
    { name: 'Team Member', role: 'Database Engineer', initial: 'D' }
  ];

  return (
    <div className="pb-16 px-4 md:px-8">
      {/* About Hero Section */}
      <div className="max-w-4xl mx-auto text-center mt-12 mb-10 reveal">
        <h1 className="text-4xl font-extrabold mb-4 background-clip-text text-transparent bg-gradient-gold font-primary uppercase tracking-wider">
          About Us
        </h1>
        <p className="text-gray-light text-[1.15rem] leading-relaxed">
          We're a passionate team of anime enthusiasts building the ultimate streaming destination for fans worldwide.
        </p>
      </div>

      <section className="max-w-5xl mx-auto flex flex-col gap-12">
        {/* Mission Statement */}
        <div className="bg-gradient-card border border-gold/15 p-6 md:p-8 rounded-xl shadow-lg backdrop-blur-glass reveal">
          <h2 className="text-2xl font-bold text-white mb-4 border-l-4 border-gold pl-3 font-primary">Our Mission</h2>
          <p className="text-gray-light text-[0.98rem] leading-relaxed">
            We believe everyone deserves access to the best anime content in a seamless and beautiful experience. 
            Our platform is built with love by anime fans, for anime fans — bringing together a vast library of series, movies, 
            and exclusive content all in one place. From classics to the latest releases, we've got you covered.
          </p>
        </div>

        {/* Features list */}
        <div className="reveal">
          <h2 className="text-2xl font-bold text-white text-center mb-8 font-primary">What We Offer</h2>
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 stagger-children">
            <div className="bg-white/3 border border-white/5 p-6 rounded-xl hover:border-gold/20 hover:shadow-gold transition-all duration-300">
              <span className="text-3xl block mb-3">🎬</span>
              <h3 className="text-lg font-bold text-white mb-2 font-primary">Vast Library</h3>
              <p className="text-gray-light text-sm">Access thousands of anime titles across every genre — from action and adventure to romance and slice of life.</p>
            </div>
            <div className="bg-white/3 border border-white/5 p-6 rounded-xl hover:border-gold/20 hover:shadow-gold transition-all duration-300">
              <span className="text-3xl block mb-3">📱</span>
              <h3 className="text-lg font-bold text-white mb-2 font-primary">Watch Anywhere</h3>
              <p className="text-gray-light text-sm">Enjoy your favorite anime on any device — desktop, tablet, or mobile with our responsive design.</p>
            </div>
            <div className="bg-white/3 border border-white/5 p-6 rounded-xl hover:border-gold/20 hover:shadow-gold transition-all duration-300">
              <span className="text-3xl block mb-3">📋</span>
              <h3 className="text-lg font-bold text-white mb-2 font-primary">Personal Watchlist</h3>
              <p className="text-gray-light text-sm">Keep track of what you're watching, plan to watch, and have completed with our smart watchlist feature.</p>
            </div>
            <div className="bg-white/3 border border-white/5 p-6 rounded-xl hover:border-gold/20 hover:shadow-gold transition-all duration-300">
              <span className="text-3xl block mb-3">🔍</span>
              <h3 className="text-lg font-bold text-white mb-2 font-primary">Smart Search</h3>
              <p className="text-gray-light text-sm">Find any anime instantly with our powerful search engine. Browse by genre, type, or just search by name.</p>
            </div>
            <div className="bg-white/3 border border-white/5 p-6 rounded-xl hover:border-gold/20 hover:shadow-gold transition-all duration-300">
              <span className="text-3xl block mb-3">🕐</span>
              <h3 className="text-lg font-bold text-white mb-2 font-primary">Continue Watching</h3>
              <p className="text-gray-light text-sm">Pick up right where you left off. We keep track of your watch history so you never lose your place.</p>
            </div>
            <div className="bg-white/3 border border-white/5 p-6 rounded-xl hover:border-gold/20 hover:shadow-gold transition-all duration-300">
              <span className="text-3xl block mb-3">🎨</span>
              <h3 className="text-lg font-bold text-white mb-2 font-primary">Beautiful Design</h3>
              <p className="text-gray-light text-sm">Enjoy a modern, immersive interface crafted with stunning animations and a premium dark theme.</p>
            </div>
          </div>
        </div>

        {/* Team list */}
        <div className="reveal">
          <h2 className="text-2xl font-bold text-white text-center mb-8 font-primary">Our Team</h2>
          <div className="grid grid-cols-2 md:grid-cols-4 gap-6 stagger-children">
            {team.map((member, i) => (
              <div key={i} className="bg-white/3 border border-white/5 p-6 rounded-xl flex flex-col items-center gap-3 text-center hover:border-gold/20 hover:shadow-gold transition-all duration-300">
                <div className="w-16 h-16 rounded-full bg-gradient-gold text-black text-2xl font-extrabold flex items-center justify-center font-primary shadow-gold">
                  {member.initial}
                </div>
                <div>
                  <h3 className="text-base font-bold text-white font-primary">{member.name}</h3>
                  <p className="text-gold/80 text-xs mt-1">{member.role}</p>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>
    </div>
  );
};

export default About;
