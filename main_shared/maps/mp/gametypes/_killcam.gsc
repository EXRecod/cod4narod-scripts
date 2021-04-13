#include maps\mp\gametypes\_hud_util;

init()
{
	precacheShader("white");
	

	level.killcam = true;


	if( level.killcam || level.dvar[ "final_killcam" ] )
		setArchive( true );
}

killcam(
	attackerNum, // entity number of the attacker
	killcamentity, // entity number of the attacker's killer entity aka helicopter or airstrike
	sWeapon, // killing weapon
	predelay, // time between player death and beginning of killcam
	offsetTime, // something to do with how far back in time the killer was seeing the world when he made the kill; latency related, sorta
	respawn, // will the player be allowed to respawn after the killcam?
	maxtime, // time remaining until map ends; the killcam will never last longer than this. undefined = no limit
	perks, // the perks the attacker had at the time of the kill
	attacker, // entity object of attacker
	sMeansOfDeath
)
{	
	self endon("disconnect");
	self endon("spawned");
	level endon("game_ended");

	if(attackerNum < 0)
		return;
		
	waittillframeend;

	// length from killcam start to killcam end
	if( sWeapon == "artillery_mp" )
	{
		if( isDefined( self.sWeaponForKillcam ) )
		{
			switch( self.sWeaponForKillcam )
			{
				case "ac180_105mm":
					camtime = 5.5;
					break;
				case "ac180_40mm":
					camtime = 5;
					break;
				case "ac180_25mm":
					camtime = 5.5;
					break;
				case "nuke_main":
					camtime = 7.5;
					break;
				case "nuke_rad":
					camtime = 2.5;
					break;
				case "agm":
					if( isDefined( level.AGMLaunchTime[ attackerNum ] ) && level.AGMLaunchTime[ attackerNum ] / 1000 < 8 )
						camtime = level.AGMLaunchTime[ attackerNum ] / 1000;
					else
						camtime = 1.5;
						
					break;
				case "artillery":
					camtime = 3.7;
					break;
				default:
					camtime = 2;
					break;
			}
		}
		else
			camtime = 2.3;
	}
	else if (sWeapon == "frag_grenade_mp")
		camtime = 5.5; // show long enough to see grenade thrown
	else
		camtime = 4;
			
	
	if( isDefined( self.hardpointVisionA ) )
	{
		self thread volkv\common::initialVisionSettings();
		self.hardpointVisionA = undefined;
	}
	
	/////////////////
	// STOCK STUFF //
	/////////////////
	if (isdefined(maxtime)) {
		if (camtime > maxtime)
			camtime = maxtime;
		if (camtime < .05)
			camtime = .05;
	}
	
	// time after player death that killcam continues for
	postdelay = 1.75;
	
	/* timeline:
	
	|        camtime       |      postdelay      |
	|                      |   predelay    |
	
	^ killcam start        ^ player death        ^ killcam end
	                                       ^ player starts watching killcam
	
	*/
	
	killcamlength = camtime + postdelay;
	
	// don't let the killcam last past the end of the round.
	if (isdefined(maxtime) && killcamlength > maxtime)
	{
		// first trim postdelay down to a minimum of 1 second.
		// if that doesn't make it short enough, trim camtime down to a minimum of 1 second.
		// if that's still not short enough, cancel the killcam.
		if (maxtime < 2)
			return;

		if (maxtime - camtime >= 1) {
			// reduce postdelay so killcam ends at end of match
			postdelay = maxtime - camtime;
		}
		else {
			// distribute remaining time over postdelay and camtime
			postdelay = 1;
			camtime = maxtime - 1;
		}
		
		// recalc killcamlength
		killcamlength = camtime + postdelay;
	}

	killcamoffset = camtime + predelay;
	
	////////////////////////////////////////////////////
	
	self SetClientDvar( "ui_ShowMenuOnly", "class" );
	
	waittillframeend;
	
	///////////////////////////////////////////////////
	///////////////////////////////////////////////////
	self notify ( "begin_killcam", getTime() );
	
	self.sessionstate = "spectator";
	self.spectatorclient = attackerNum;
	self.killcamentity = killcamentity;
	self.archivetime = killcamoffset;
	self.killcamlength = killcamlength;
	self.psoffsettime = offsetTime;

	// ignore spectate permissions
	self allowSpectateTeam("allies", true);
	self allowSpectateTeam("axis", true);
	self allowSpectateTeam("freelook", true);
	self allowSpectateTeam("none", true);
	
	// wait till the next server frame to allow code a chance to update archivetime if it needs trimming
	wait 0.05;

	if ( self.archivetime <= predelay ) // if we're not looking back in time far enough to even see the death, cancel
	{
		self.sessionstate = "dead";
		self.spectatorclient = -1;
		self.killcamentity = -1;
		self.archivetime = 0;
		self.psoffsettime = 0;
		self SetClientDvar( "ui_ShowMenuOnly", "" );
		
		return;
	}
	
	self.killcam = true;
	///////////////////////////////////////////////////
	
	data = volkv\killcam_settings::killcamData( sWeapon, sMeansOfDeath );
	self thread hud( attacker, data );
	
	//////////////////////////////////////////////////
	//////////////////////////////////////////////////
	self thread spawnedKillcamCleanup();
	self thread endedKillcamCleanup();
	self thread waitSkipKillcamButton();
	self thread waitKillcamTime();

	self waittill("end_killcam");
	
	waittillframeend;

	self endKillcam();

	self.sessionstate = "dead";
	self.spectatorclient = -1;
	self.killcamentity = -1;
	self.archivetime = 0;
	self.psoffsettime = 0;
}

hud( attacker, data )
{
	self endon("disconnect");
	self endon("spawned");
	level endon("game_ended");
	
	self.kc_hud[ 0 ] = newClientHudElem( self );
	self.kc_hud[ 0 ].alpha = 1;
	self.kc_hud[ 0 ] setShader( "white", 1, 79 );
	self.kc_hud[ 0 ].color	= ( 1, 1, 1 );
	self.kc_hud[ 0 ].horzAlign = "center";
	self.kc_hud[ 0 ].vertAlign = "fullscreen";
	self.kc_hud[ 0 ].archived = false;
	self.kc_hud[ 0 ].sort = 1;
	self.kc_hud[ 0 ].y = 22;
	self.kc_hud[ 0 ].x = 25;
	self.kc_hud[ 0 ].alignX = "center";
	
	self.kc_hud[ 1 ] = newClientHudElem( self );
	self.kc_hud[ 1 ].alpha = 0.8;
	self.kc_hud[ 1 ] setShader( "white", 440, 16 );
	self.kc_hud[ 1 ].color	= ( 1, .7, 0 );
	self.kc_hud[ 1 ].horzAlign = "center";
	self.kc_hud[ 1 ].vertAlign = "fullscreen";
	self.kc_hud[ 1 ].archived = false;
	self.kc_hud[ 1 ].sort = 2;
	self.kc_hud[ 1 ].y = 15;
	self.kc_hud[ 1 ].alignX = "center";
	
	self.kc_hud[ 2 ] = newClientHudElem( self );
	self.kc_hud[ 2 ].alpha = 0.65;
	self.kc_hud[ 2 ] setShader( "white", 440, 70 );
	self.kc_hud[ 2 ].color	= ( 0, 0, 0 );
	self.kc_hud[ 2 ].horzAlign = "center";
	self.kc_hud[ 2 ].vertAlign = "fullscreen";
	self.kc_hud[ 2 ].archived = false;
	self.kc_hud[ 2 ].y = 31;
	self.kc_hud[ 2 ].sort = 0;
	self.kc_hud[ 2 ].alignX = "center";
	
	self.kc_hud[ 3 ] = newClientHudElem( self );
	self.kc_hud[ 3 ].alpha = 1;
	self.kc_hud[ 3 ] setShader( "white", 1, 79 );
	self.kc_hud[ 3 ].color	= ( 1, 1, 1 );
	self.kc_hud[ 3 ].horzAlign = "center";
	self.kc_hud[ 3 ].vertAlign = "fullscreen";
	self.kc_hud[ 3 ].archived = false;
	self.kc_hud[ 3 ].sort = 1;
	self.kc_hud[ 3 ].y = 22;
	self.kc_hud[ 3 ].x = -40;
	self.kc_hud[ 3 ].alignX = "center";
	
	if( isDefined( attacker ) && isPlayer( attacker ) )
		string = attacker.name;
	else if( isDefined( attacker ) && !isPlayer( attacker ) )
		string = "World";
	else
		string = "Disconnected";
	
	if( string.size >= 17 )
		string = getSubStr( string, 0, 17 );
	
	self.kc_hud[ 4 ] = newClientHudElem( self );
	self.kc_hud[ 4 ].alpha = 1;
	self.kc_hud[ 4 ].y = 15;
	self.kc_hud[ 4 ].x = -218;
	self.kc_hud[ 4 ].alignX = "left";
	self.kc_hud[ 4 ].horzAlign = "center";
	self.kc_hud[ 4 ].archived = false;
	self.kc_hud[ 4 ].fontscale = 1.4;
	self.kc_hud[ 4 ] setText( "^0"+self volkv\_common::getLangString("KILLCAM_HINT_KILLEDBY")+"  ^7" + string );
	self.kc_hud[ 4 ].sort = 3;
	
	if( !isDefined( attacker ) || !isPlayer( attacker ) )
		return;
	
	attID = attacker getPlayerID();
	
	self.kc_hud[ 5 ] = newClientHudElem( self );
	self.kc_hud[ 5 ].alpha = 1;
	self.kc_hud[ 5 ].y = 15;
	self.kc_hud[ 5 ].x = 218;
	self.kc_hud[ 5 ].alignX = "right";
	self.kc_hud[ 5 ].horzAlign = "center";
	self.kc_hud[ 5 ].archived = false;
	self.kc_hud[ 5 ].fontscale = 1.4;
	self.kc_hud[ 5 ] setText( "^0"+ self volkv\_common::getLangString("KILLCAM_HINT_ME") +  " ^7" + self.pers[ "youVSfoe" ][ "killed" ][ attID ] + " - " + self.pers[ "youVSfoe" ][ "killedBy" ][ attID ] + "  ^0"+self volkv\_common::getLangString("KILLCAM_HINT_FOE") );
	self.kc_hud[ 5 ].sort = 3;
	
	self.kc_hud[ 6 ] = newClientHudElem( self );
	self.kc_hud[ 6 ].alpha = 1;
	self.kc_hud[ 6 ].y = 30;
	self.kc_hud[ 6 ].x = -130;
	self.kc_hud[ 6 ].alignX = "center";
	self.kc_hud[ 6 ].horzAlign = "center";
	self.kc_hud[ 6 ].archived = false;
	self.kc_hud[ 6 ].fontscale = 1.4;
	self.kc_hud[ 6 ] setText( data.name );
	self.kc_hud[ 6 ].sort = 3;
	
	self.kc_hud[ 7 ] = newClientHudElem( self );
	self.kc_hud[ 7 ].alpha = 1;
	self.kc_hud[ 7 ].y = 37;
	self.kc_hud[ 7 ].x = -130;
	self.kc_hud[ 7 ].alignX = "center";
	self.kc_hud[ 7 ].horzAlign = "center";
	self.kc_hud[ 7 ].archived = false;
	self.kc_hud[ 7 ].fontscale = 1.4;
	self.kc_hud[ 7 ] setShader( data.icon, 90, 60 );
	self.kc_hud[ 7 ].sort = 3;
	
	self.kc_hud[ 8 ] = newClientHudElem( self );
	self.kc_hud[ 8 ].alpha = 1;
	self.kc_hud[ 8 ].y = 30;
	self.kc_hud[ 8 ].x = -7;
	self.kc_hud[ 8 ].alignX = "center";
	self.kc_hud[ 8 ].horzAlign = "center";
	self.kc_hud[ 8 ].archived = false;
	self.kc_hud[ 8 ].fontscale = 1.4;
	goodrank = int(attacker.pers[ "rank" ]) + 1;
	self.kc_hud[ 8 ] setText(self volkv\_common::getLangString("KILLCAM_HINT_RANK")+": "+ goodrank);
	self.kc_hud[ 8 ].sort = 3;
	
	self.kc_hud[ 9 ] = newClientHudElem( self );
	self.kc_hud[ 9 ].alpha = 1;
	self.kc_hud[ 9 ].y = 46;
	self.kc_hud[ 9 ].x = -7;
	self.kc_hud[ 9 ].alignX = "center";
	self.kc_hud[ 9 ].horzAlign = "center";
	self.kc_hud[ 9 ].archived = false;
	self.kc_hud[ 9 ].fontscale = 1.4;
	self.kc_hud[ 9 ] setShader( maps\mp\gametypes\_rank::getRankInfoIcon( attacker.pers[ "rank" ], attacker.pers[ "prestige" ] ), 50, 50 );
	self.kc_hud[ 9 ].sort = 3;
	
	// 24 chars per line - or was it 34 lol
	quote = [];
	quote[ 0 ] = self volkv\_common::getLangString("KILLCAM_HINT1");
	quote[ 1 ] = self volkv\_common::getLangString("KILLCAM_HINT2");
 	
	self.kc_hud[ 10 ] = newClientHudElem( self );
	self.kc_hud[ 10 ].alpha = 1;
	self.kc_hud[ 10 ].y = 35;
	self.kc_hud[ 10 ].x = 125;
	self.kc_hud[ 10 ].alignX = "center";
	self.kc_hud[ 10 ].horzAlign = "center";
	self.kc_hud[ 10 ].archived = false;
	self.kc_hud[ 10 ].fontscale = 1.4;
	self.kc_hud[ 10 ] setText( quote[ randomInt( quote.size ) ] );
	self.kc_hud[ 10 ].sort = 3;
}

waitKillcamTime()
{
	self endon("disconnect");
	self endon("end_killcam");

	wait(self.killcamlength - 0.05);
	self notify("end_killcam");
}

waitSkipKillcamButton()
{
	self endon("disconnect");
	self endon("end_killcam");

	while(self useButtonPressed())
		wait .05;

	while(!(self useButtonPressed()))
		wait .05;

	self notify("end_killcam");
}

endKillcam()
{
	waittillframeend;
	
	if( isArray( self.kc_hud ) )
	{
		for( i = 0; i < self.kc_hud.size; i++ )
		{
			if( isDefined( self.kc_hud[ i ] ) )
				self.kc_hud[ i ] destroy();
		}
	}
	
	self.kc_hud = undefined;
	
	self.visiondata = undefined;

	
	self.killcam = undefined;
	self.hardpointVision = undefined;
	
	self SetClientDvar( "ui_ShowMenuOnly", "" );
	self.sWeaponForKillcam = undefined;
	
	self thread maps\mp\gametypes\_spectating::setSpectatePermissions();
}

spawnedKillcamCleanup()
{
	self endon("end_killcam");
	self endon("disconnect");

	self waittill("spawned");
	self endKillcam();
}

spectatorKillcamCleanup( attacker )
{
	self endon("end_killcam");
	self endon("disconnect");
	attacker endon ( "disconnect" );

	attacker waittill ( "begin_killcam", attackerKcStartTime );
	waitTime = max( 0, (attackerKcStartTime - self.deathTime) - 50 );
	wait (waitTime);
	self endKillcam();
}

endedKillcamCleanup()
{
	self endon("end_killcam");
	self endon("disconnect");

	level waittill("game_ended");
	self endKillcam();
}
