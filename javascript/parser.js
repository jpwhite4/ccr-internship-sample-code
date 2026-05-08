#!/usr/bin/env node

const fs = require('fs');
const readline = require('readline');

// Parse out a slurm accounting log file created with the following sacct command
//
// TZ=UTC sacct --clusters *cluster* --allusers \
//    --parsable2 --noheader --allocations --duplicates \
//    --format jobid,jobidraw,cluster,partition,account,group,gid,\
// user,uid,submit,eligible,start,end,elapsed,exitcode,state,nnodes,\
// ncpus,reqcpus,reqmem,reqgres,reqtres,timelimit,nodelist,jobname \
//    --state CANCELLED,COMPLETED,FAILED,NODE_FAIL,PREEMPTED,TIMEOUT \
//    --starttime 2013-01-01T00:00:00 --endtime 2013-01-01T23:59:59 \
//    >/tmp/slurm.log
//
// and convert to a array of dictionaries. There are 25 fields to parse and the delimiter
// is the pipe character '|'.
//
async function parse(filename) {
  const fieldnames = 'jobid,jobidraw,cluster,partition,qos,account,group,gid,user,uid,submit,eligible,start,end,elapsed,exitcode,state,nnodes,ncpus,reqcpus,reqmem,reqtres,alloctres,timelimit,nodelist,jobname'.split(',');
  const lines = readline.createInterface({
    input: fs.createReadStream(filename),
  });

  const data = [];
  for await (const line of lines) {
    const datum = {};
    const tokens = line.split('|');
    for (let i = 0; i < tokens.length; i++) {
      const field = fieldnames[i];
      if (!field) {
        throw new Error('Unknown field');
      }
      datum[field] = tokens[i];
    }
    data.push(datum);
  }

  return data;
}

if (process.argv.length < 3) {
  console.error('Usage:', process.argv[1], '[filename]');
  process.exit(1);
}

parse(process.argv[2]).then((data, err) => {
  if (err) {
    console.error(err);
    return;
  }
  process.stdout.write(`${JSON.stringify(data, null, 4)}\n`);
});
