<?php
namespace plibv4\input;
/**
 * Enum to determine style how selectable items are indexed:
 * SOURCE: as in source, say m => male, f => female, o => other
 * ZERO: shown as zero indexed: 0 => male, 1 => female, 2 => other
 * NATURAL: shown as one indexed: 1 => male, 2 => female, 3 => other
 * 
 * m, f or o will always be used as return value.
 */
enum IndexStyle {
	case SOURCE;
	case ZERO;
	case NATURAL;
}