<?php

/**
 * Parse out a slurm accounting log file created with the following sacct command
 *
 * TZ=UTC sacct --clusters *cluster* --allusers \
 *    --parsable2 --noheader --allocations --duplicates \
 *    --format jobid,jobidraw,cluster,partition,account,group,gid,\
 * user,uid,submit,eligible,start,end,elapsed,exitcode,state,nnodes,\
 * ncpus,reqcpus,reqmem,reqgres,reqtres,timelimit,nodelist,jobname \
 *    --state CANCELLED,COMPLETED,FAILED,NODE_FAIL,PREEMPTED,TIMEOUT \
 *    --starttime 2013-01-01T00:00:00 --endtime 2013-01-01T23:59:59 \
 *    >/tmp/slurm.log
 *
 * and convert to a array of dictionaries. There are 25 fields to parse and the 
 * delimiter is the pipe character '|'.
 *
 * @param string $filename the name of the file to read and parse
 *
 * @return array the parsed data from the input file
 * @throws Exception the input file had an unknown field
 */
function parse($filename)
{
    $fieldnames = explode(',', 'jobid,jobidraw,cluster,partition,qos,account,group,gid,user,uid,submit,eligible,start,end,elapsed,exitcode,state,nnodes,ncpus,reqcpus,reqmem,reqtres,alloctres,timelimit,nodelist,jobname');

    $data = [];

    $handle = fopen($filename, 'r');
    if ($handle === false) {
        throw new Exception('Error opening file ' . $filename);
    }

    while (($line = fgets($handle, 4096)) !== false) {
        $datum = array();
        foreach (explode('|', trim($line)) as $idx => $token) {
            $field = $fieldnames[$idx];
            if (!$field) {
                throw new Exception('Unknown field');
            }
            $datum[$fieldnames[$idx]] = $token;
        }
        $data[] = $datum;
    }

    fclose($handle);

    return $data;
}

if (count($argv) < 2) {
    error_log('Usage: ' . $argv[0] . ' [FILENAME]');
    exit(1);
}

$data = parse($argv[1]);
print json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
