name := """broadcast_server"""
organization := "com.shrize.eventsServer"

version := "0.1"

lazy val root = (project in file(".")).enablePlugins(PlayScala).enablePlugins(SbtTwirl).disablePlugins(PlayFilters)

scalaVersion := "2.12.19"

libraryDependencies += guice
// https://mvnrepository.com/artifact/com.typesafe/config
libraryDependencies += "com.typesafe" % "config" % "1.2.1"
libraryDependencies += "org.scalatestplus.play" %% "scalatestplus-play" % "5.0.0" % Test

